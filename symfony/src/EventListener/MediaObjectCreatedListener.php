<?php

namespace App\EventListener;

use App\Entity\MediaObject;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\KernelInterface;

class MediaObjectCreatedListener
{
    private KernelInterface $appKernel;
    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }

    public function prePersist(MediaObject $mediaObject, LifecycleEventArgs $event)
    {
        $connection = $event->getEntityManager()->getConnection();

        $fileContents = file_get_contents($mediaObject->file->getPathname());

        //psql doesn't recognize this for some reason
        $fileContents = str_replace("<?xml version=\"1.0\"?>", "", $fileContents); 

        //create temp table with xml contents
        $sql = "CREATE TABLE xmldata AS SELECT xml $$".$fileContents."$$ AS data;";
        $sql2 = "INSERT INTO product(name, description, weight, category) SELECT xmltable.*
                FROM xmldata,
                    XMLTABLE('//products/product'
                            PASSING data
                            COLUMNS
                                    \"name\" varchar(255),
                                    \"description\" text, 
                                    \"weight\" varchar(255), 
                                    \"category\" varchar(255));";
        $sql3 = "DROP TABLE xmldata;";

        $csvFileName = "products_".time().".csv";
        $csvContentUrl = "/media/csv/".$csvFileName;
        $csvFilePath = $this->appKernel->getProjectDir()."/public". $csvContentUrl;
        $sql4 = "COPY product TO '" . $csvFilePath . "' DELIMITER ',' CSV HEADER;";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $stmt = $connection->prepare($sql2);
        $stmt->execute();
        $stmt = $connection->prepare($sql3);
        $stmt->execute();
        $stmt = $connection->prepare($sql4);
        $stmt->execute();

        $mediaObject->csvContentUrl = $csvContentUrl;

    }
}
