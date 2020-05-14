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

namespace Vzaica\Extractor;

use Vzaica\Extractor\Adapter\Interfaces\ExtractorAdapterInterface;
use Vzaica\Extractor\Exception\AdapterNotAvailableException;
use Vzaica\Extractor\Exception\ExtensionNotSupportedException;
use Vzaica\Extractor\Exception\FileNotFoundException;
use Vzaica\Extractor\Filesystem\Interfaces\DirectoryInterface;
use Vzaica\Extractor\Resolver\ExtensionResolver;
use Vzaica\Extractor\Resolver\Interfaces\ExtensionResolverInterface;
use Symfony\Component\Finder\Finder;

/**
 * Class Extractor
 */
class Extractor
{
    /**
     * @var DirectoryInterface
     *
     * Directory
     */
    protected $directory;

    /**
     * @var ExtensionResolverInterface
     *
     * Directory
     */
    protected $extensionResolver;

    /**
     * Construct method
     *
     * @param DirectoryInterface $directory Directory
     * @param ExtensionResolverInterface|null $extensionResolver
     */
    public function __construct(
        DirectoryInterface $directory,
        ExtensionResolverInterface $extensionResolver = null
    )
    {
        if (is_null($extensionResolver)) {
            $extensionResolver = new ExtensionResolver();
        }

        $this->directory = $directory;
        $this->extensionResolver = $extensionResolver;
    }

    /**
     * Extract files from compressed file
     *
     * @param string $filePath Compressed file path
     *
     * @return Finder Finder instance with all files added
     *
     * @throws ExtensionNotSupportedException Exception not found
     * @throws AdapterNotAvailableException   Adapter not available
     * @throws FileNotFoundException          File not found
     */
    public function extractFromFile($filePath)
    {
        if (!is_file($filePath)) {

            throw new FileNotFoundException($filePath);
        }

        $extension = $this->extensionResolver->getExtensionGivenFilePath($filePath);
        $this->checkDirectory();

        $extractorAdapterNamespace = $this->extensionResolver->getAdapterNamespaceGivenExtension($extension);

        $extractorAdapter = $this
            ->instanceExtractorAdapter($extractorAdapterNamespace);

        if (!$extractorAdapter->isAvailable()) {

            throw new AdapterNotAvailableException($extractorAdapter->getIdentifier());
        }

        return $extractorAdapter->extract($filePath);
    }

    /**
     * Instance new extractor adapter given its namespace
     *
     * @param string $extractorAdapterNamespace Extractor Adapter namespace
     *
     * @return ExtractorAdapterInterface Extractor adapter
     */
    protected function instanceExtractorAdapter($extractorAdapterNamespace)
    {
        return new $extractorAdapterNamespace($this->directory);
    }

    /**
     * Check directory existence and integrity
     *
     * @return $this self Object
     */
    protected function checkDirectory()
    {
        $directoryPath = $this
            ->directory
            ->getDirectoryPath();

        if (!is_dir($directoryPath)) {

            mkdir($directoryPath);
        }

        return $this;
    }
}
