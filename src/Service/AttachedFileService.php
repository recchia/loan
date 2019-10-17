<?php


namespace App\Service;


use App\Entity\AttachedFile;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AttachedFileService
{
    public const URI = '/web_clientes/addAttachedFile';

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $filePath;

    /**
     * AttachedFileService constructor.
     * @param HttpClientInterface $client
     * @param string $filePath
     */
    public function __construct(HttpClientInterface $client, string $filePath)
    {
        $this->client = $client;
        $this->filePath = $filePath;
    }

    public function makeLegacyRequest(AttachedFile $file)
    {
        //$payload = $this->getPayload($file);

        //return $this->client->post(static::URI, $payload);
    }

    protected function getPayload(AttachedFile $file): array
    {
        $fields = [
            'multipart' => [
                [
                    'name' => 'description',
                    'contents' => $file->getDescription()
                ],
                [
                    'name' => 'fileType',
                    'contents' => $file->getFileType()->getId()
                ],
                [
                    'name' => 'clientId',
                    'contents' => $file->getLoan()->getLegacyClientId()
                ],
                [
                    'name' => 'attach',
                    'contents' => fopen($this->filePath . $file->getImage(), 'r'),
                    'filename' => $file->getImage()
                ]
            ]
        ];

        return $fields;
    }

}