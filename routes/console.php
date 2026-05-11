<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('products:import-images {--path=} {--list-products : Print product matching keys and exit} {--dry-run : Only show what would change} {--force : Overwrite gambar_utama even if already set}', function () {
    $sourcePath = $this->option('path') ?: 'C:\\Users\\OMEN\\Downloads\\gambar\\gambar';
    $shouldListProducts = (bool) $this->option('list-products');
    $isDryRun = (bool) $this->option('dry-run');
    $isForce = (bool) $this->option('force');

    if (! is_dir($sourcePath)) {
        $this->error("Folder tidak ditemukan: {$sourcePath}");
        $this->line('Contoh: php artisan products:import-images --path="C:\\path\\ke\\folder-gambar"');
        return 1;
    }

    $files = collect(File::files($sourcePath))
        ->filter(function (\SplFileInfo $file) {
            return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true);
        })
        ->values();

    if ($files->isEmpty()) {
        $this->warn('Tidak ada file gambar (jpg/jpeg/png/webp) di folder tersebut.');
        return 0;
    }

    $this->info('Membaca produk dari database...');
    $products = Product::query()->get(['id', 'nama_produk', 'gambar_utama']);
    $this->line('Total produk: ' . $products->count());

    $productIndex = [];
    $productTokens = [];

    $normalizeTokens = function (array $tokens): array {
        $synonyms = [
            'layar' => 'monitor',
        ];

        $normalized = [];
        foreach ($tokens as $token) {
            $token = strtolower($token);
            $token = $synonyms[$token] ?? $token;
            if ($token !== '') {
                $normalized[] = $token;
            }
        }

        return array_values(array_unique($normalized));
    };

    $tokenize = function (string $text) use ($normalizeTokens): array {
        // Split CamelCase-ish strings (e.g. MouseLogitechG502)
        $text = preg_replace('/(?<!^)([A-Z])/', ' $1', $text);
        $text = preg_replace('/[^A-Za-z0-9]+/', ' ', (string) $text);
        $parts = preg_split('/\s+/', trim($text)) ?: [];
        return $normalizeTokens(array_filter($parts, fn ($p) => $p !== ''));
    };
    foreach ($products as $product) {
        $key = Str::of($product->nama_produk)->slug('')->lower()->toString();
        if ($key === '') {
            continue;
        }
        $productIndex[$key][] = $product;
        $productTokens[$product->id] = $tokenize($product->nama_produk);
    }

    if ($shouldListProducts) {
        $this->newLine();
        $this->info('Daftar produk & key pencocokan (nama_produk -> key)');
        foreach ($products as $product) {
            $key = Str::of($product->nama_produk)->slug('')->lower()->toString();
            $this->line("- {$product->id} | {$product->nama_produk} | {$key}");
        }
        return 0;
    }

    Storage::disk('public')->makeDirectory('products');

    $matched = 0;
    $updated = 0;
    $skippedAlreadyHasImage = 0;
    $skippedAmbiguous = 0;
    $unmatchedFiles = [];

    foreach ($files as $file) {
        $baseName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        $fileKey = Str::of($baseName)->slug('')->lower()->toString();

        $candidates = $productIndex[$fileKey] ?? [];
        $matchMode = 'exact';

        // Fuzzy match: if exact key not found, match by token subset.
        if (count($candidates) === 0) {
            $fileTokens = $tokenize($baseName);

            if (! empty($fileTokens)) {
                $tokenMatches = [];
                foreach ($products as $product) {
                    $pTokens = $productTokens[$product->id] ?? [];
                    // fileTokens ⊆ productTokens
                    if (count(array_diff($fileTokens, $pTokens)) === 0) {
                        $tokenMatches[] = $product;
                    }
                }

                if (count($tokenMatches) === 1) {
                    $candidates = $tokenMatches;
                    $matchMode = 'token';
                } elseif (count($tokenMatches) > 1) {
                    // Prefer the candidate with the smallest extra tokens.
                    usort($tokenMatches, function ($a, $b) use ($fileTokens, $productTokens) {
                        $aExtra = count(array_diff(($productTokens[$a->id] ?? []), $fileTokens));
                        $bExtra = count(array_diff(($productTokens[$b->id] ?? []), $fileTokens));
                        return $aExtra <=> $bExtra;
                    });

                    $best = $tokenMatches[0];
                    $bestExtra = count(array_diff(($productTokens[$best->id] ?? []), $fileTokens));
                    $second = $tokenMatches[1];
                    $secondExtra = count(array_diff(($productTokens[$second->id] ?? []), $fileTokens));

                    if ($bestExtra < $secondExtra) {
                        $candidates = [$best];
                        $matchMode = 'token';
                    }
                }
            }
        }

        if (count($candidates) === 0) {
            $unmatchedFiles[] = $file->getFilename();
            continue;
        }

        if (count($candidates) > 1) {
            $this->warn("Ambigu: file '{$file->getFilename()}' cocok ke lebih dari 1 produk (key: {$fileKey}).");
            $skippedAmbiguous++;
            continue;
        }

        /** @var \App\Models\Product $product */
        $product = $candidates[0];
        $matched++;

        if (! $isForce && ! empty($product->gambar_utama)) {
            $skippedAlreadyHasImage++;
            continue;
        }

        $targetName = $file->getFilename();
        $targetPath = 'products/' . $targetName;

        if (! $isDryRun) {
            if (! Storage::disk('public')->exists($targetPath)) {
                Storage::disk('public')->putFileAs('products', $file->getRealPath(), $targetName);
            }

            $product->gambar_utama = $targetPath;
            $product->save();
        }

        $updated++;
        $this->line(($isDryRun ? '[DRY]' : '[OK]') . ($matchMode === 'token' ? ' [FUZZY]' : '') . " {$product->id} - {$product->nama_produk} <= {$targetPath}");
    }

    $this->newLine();
    $this->info('Ringkasan');
    $this->line("- Total file gambar: {$files->count()}");
    $this->line("- File match ke produk: {$matched}");
    $this->line("- Produk diupdate: {$updated}" . ($isDryRun ? ' (dry-run)' : ''));
    $this->line("- Skip (produk sudah punya gambar): {$skippedAlreadyHasImage}");
    $this->line("- Skip (ambiguous): {$skippedAmbiguous}");
    $this->line("- File tidak ketemu produk: " . count($unmatchedFiles));

    if (! empty($unmatchedFiles)) {
        $this->newLine();
        $this->warn('File yang tidak match (contoh 20 pertama):');
        foreach (array_slice($unmatchedFiles, 0, 20) as $name) {
            $this->line('- ' . $name);
        }
    }

    $this->newLine();
    $this->line('Catatan: Pastikan sudah menjalankan `php artisan storage:link` agar URL /storage/... bisa diakses.');

    return 0;
})->purpose('Import gambar produk massal dari folder lokal dan isi gambar_utama berdasarkan nama produk');
