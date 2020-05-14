<?php

/*
 * This file is part of the Extractor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Vzaica\Extractor\Resolver;

use Vzaica\Extractor\Exception\ExtensionNotSupportedException;
use Vzaica\Extractor\Resolver\Interfaces\ExtensionResolverInterface;

/**
 * Class ExtensionResolver
 */
class ExtensionResolver implements ExtensionResolverInterface
{
    /**
     * Return a extractor adapter namespace given an extension
     *
     * @param string $fileExtension File extension
     *
     * @return string Adapter namespace
     *
     * @throws ExtensionNotSupportedException Exception not found
     */
    public function getAdapterNamespaceGivenExtension($fileExtension)
    {
        $adapterNamespace = '\Vzaica\Extractor\Adapter\\';

        switch ($fileExtension) {

            case 'zip':
                $adapterNamespace .= 'ZipExtractorAdapter';
                break;

            case 'rar':
                $adapterNamespace .= 'RarExtractorAdapter';
                break;

            case 'phar':
                $adapterNamespace .= 'PharExtractorAdapter';
                break;

            case 'tar':
                $adapterNamespace .= 'TarExtractorAdapter';
                break;

            case 'gz':
                $adapterNamespace .= 'TarGzExtractorAdapter';
                break;

            case 'bz2':
                $adapterNamespace .= 'TarBz2ExtractorAdapter';
                break;

            default:
                throw new ExtensionNotSupportedException($fileExtension);
        }

        return $adapterNamespace;
    }

    public function isValidExtension($extension)
    {
        try {
            $this->getAdapterNamespaceGivenExtension($extension);

            return true;
        } catch (ExtensionNotSupportedException $exception) {
            return false;
        }
    }

    public function getExtensionGivenFilePath($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if (!$this->isValidExtension($extension)) {
            $mime_type = mime_content_type($filePath);

            if ($mime_type == 'application/zip' || $mime_type == 'application/x-7z-compressed') {
                $extension = 'zip';
            } elseif ($mime_type == 'application/x-rar') {
                $extension = 'rar';
            }
        }

        return $extension;
    }
}
