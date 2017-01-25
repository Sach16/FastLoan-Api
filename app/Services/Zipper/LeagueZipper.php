<?php

namespace Whatsloan\Services\Zipper;

use Aws\S3\S3Client;

class LeagueZipper implements IZip
{
    /**
     * @var \ZipArchive
     */
    protected $zip;

    /**
     * @var S3Client
     */
    protected $s3;

    /**
     * @var
     */
    protected $bucket;

    /**
     * LeagueZipper constructor.
     */
    public function __construct()
    {
        $this->zip = new \ZipArchive;
        $this->s3 = new S3Client([
            'credentials' => [
                'key'    => env('AWS_KEY'),
                'secret' => env('AWS_SECRET'),
            ],
            'region' => env('AWS_REGION'),
            'version' => 'latest',
        ]);
        $this->s3->registerStreamWrapper();
        $this->bucket = env('S3_BUCKET');
    }

    /**
     * Zip all files in path and return
     *
     * @param $path
     * @param $filename
     * @return mixed
     */
    public function archive($path, $filename)
    {
        $time = microtime(true);
        $objects = $this->s3->getIterator('ListObjects', array(
            'Bucket' => $this->bucket,
            'Prefix' => $path,
        ));
        $this->removeOldDownload(storage_path("app/public/$filename"));
        $this->zip->open(storage_path("app/public/$filename"), \ZipArchive::CREATE);

        foreach ($objects as $object) {
            $contents = file_get_contents("s3://{$this->bucket}/{$object['Key']}");
            $this->zip->addFromString($object['Key'], $contents);
        }
        $this->zip->close();
        $this->downloadNew(storage_path("app/public/$filename"));
    }

    /**
     * @param $filename
     */
    public function removeOldDownload($filename)
    {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * @param $filename
     */
    public function downloadNew($filename)
    {
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile("$filename");
        exit;
    }
}