<?php

namespace YusamHub\AppExt\Api;

class SwaggerUiExt
{
    /**
     * @param string $vendorFullPath
     * @param string $publicFullPath
     * @return array
     */
    public static function install(string $vendorFullPath, string $publicFullPath): array
    {
        $uiDistPath = rtrim($vendorFullPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "swagger-api/swagger-ui/dist";
        if (!is_dir($uiDistPath)) {
            throw new \RuntimeException(sprintf("Swagger dist path [%s] not exists", $uiDistPath));
        }
        if (!is_dir($publicFullPath)) {
            throw new \RuntimeException(sprintf("Public path [%s] not exists", $publicFullPath));
        }

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->name('*');
        $finder->files();
        $finder->in($uiDistPath);

        if (!$finder->hasResults()) {
            throw new \RuntimeException("Files not exists in path: " . $uiDistPath);
        }

        $out = [];
        foreach ($finder as $file) {
            $newFilename = $publicFullPath . "/" . $file->getFilename();
            if (copy($file->getPathname(), $newFilename)) {
                $out[] = $newFilename;
            }
        }

        if ($finder->count() !== count($out)) {
            throw new \RuntimeException(sprintf("Total files [%d], but copied [%d]", $finder->count(), count($out)));
        }

        return $out;
    }

    /**
     * @param string $publicSwaggerUiFullPath
     * @param string $swaggerUri
     * @param string $fileUri
     * @return string
     */
    public static function replaceIndexHtml(string $publicSwaggerUiFullPath, string $swaggerUri = '/swagger-ui', string $fileUri = '/open-api'): string
    {
        $htmlFile = $publicSwaggerUiFullPath . "/index.html";

        if (!file_exists($htmlFile)) {
            throw new \RuntimeException("File not exists: " . $htmlFile);
        }

        $html = file_get_contents($htmlFile);

        $html = str_replace('href="./swagger-ui.css"','href="'.$swaggerUri.'/swagger-ui.css"', $html);
        $html = str_replace('href="./favicon-32x32.png"','href="'.$swaggerUri.'/favicon-32x32.png"', $html);
        $html = str_replace('href="./favicon-16x16.png"','href="'.$swaggerUri.'/favicon-16x16.png"', $html);

        $html = str_replace('src="./swagger-ui-bundle.js"','src="'.$swaggerUri.'/swagger-ui-bundle.js"', $html);
        $html = str_replace('src="./swagger-ui-standalone-preset.js"','src="'.$swaggerUri.'/swagger-ui-standalone-preset.js"', $html);

        $search = 'url: "https://petstore.swagger.io/v2/swagger.json",';
        $html = str_replace($search, $search . "\nvalidatorUrl: null,\n", $html);

        $replaceWith = 'window.location.protocol + "//" + window.location.hostname + "'. $swaggerUri . $fileUri . '"';

        return str_replace('"https://petstore.swagger.io/v2/swagger.json"', $replaceWith, $html);
    }
}