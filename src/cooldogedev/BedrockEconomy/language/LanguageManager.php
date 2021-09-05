<?php

/**
 *  Copyright (c) 2021 cooldogedev
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
 */

declare(strict_types=1);

namespace cooldogedev\BedrockEconomy\language;

use cooldogedev\BedrockEconomy\BedrockEconomy;
use pocketmine\utils\TextFormat;

final class LanguageManager
{
    protected const DEFAULT_LANGUAGE = "english";
    protected const LANGUAGES_MAP = [
        "english" => "en-US"
    ];

    protected static string $language;
    protected static array $translations;

    public static function init(BedrockEconomy $plugin, ?string $language)
    {
        $languagesFolder = $plugin->getDataFolder() . "languages";
        @mkdir($languagesFolder);
        foreach (LanguageManager::LANGUAGES_MAP as $languageCode) {
            $plugin->saveResource($languagesFolder . DIRECTORY_SEPARATOR . $languageCode . ".yml");
        }
        if (!isset(LanguageManager::LANGUAGES_MAP[$language]) || !$language) {
            $language = LanguageManager::DEFAULT_LANGUAGE;
        }
        LanguageManager::$language = $language;
        LanguageManager::$translations = yaml_parse_file(
            $languagesFolder . DIRECTORY_SEPARATOR . LanguageManager::LANGUAGES_MAP[$language]
        );
    }

    public static function getTranslations(): array
    {
        return LanguageManager::$translations;
    }

    public static function getTranslation(string $translation, array $variables = []): ?string
    {
        return LanguageManager::hasTranslation($translation) ?
            TextFormat::colorize(LanguageManager::translate($translation, $variables)) :
            null;
    }

    public static function hasTranslation(string $translation): bool
    {
        return isset(LanguageManager::$translations[$translation]);
    }

    protected static function translate(string $translation, array $variables = []): string
    {
        return str_replace(array_keys($variables), array_values($variables), LanguageManager::$translations[$translation]);
    }

    public static function getArray(string $translation): ?array
    {
        return (array)LanguageManager::$translations[$translation] ?? null;
    }

    public static function getLanguage(): string
    {
        return LanguageManager::$language;
    }
}