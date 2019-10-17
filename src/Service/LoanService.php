<?php


namespace App\Service;


use App\Entity\Loan;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LoanService
{
    public const URI = '/web_clientes/add_loan';

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $legacyUrl;

    /**
     * LoanService constructor.
     * @param HttpClientInterface $httpClient
     * @param string $legacyUrl
     */
    public function __construct(HttpClientInterface $httpClient, string $legacyUrl)
    {
        $this->httpClient = $httpClient;
        $this->legacyUrl = $legacyUrl;
    }

    public function makeLegacyRequest(Loan $loan): ResponseInterface
    {
        /*$options = $this->getOptions($loan);

        try {
            return $this->httpClient->post(static::URI, $options);
        } catch (ClientException $exception) {
            return $exception->getResponse();
        }*/
    }

    /**
     * Prepare fields for request
     *
     * @param Loan $loan
     * @return array
     */
    protected function getOptions(Loan $loan): array
    {
        $origin = $this->getOrigin($loan);

        $fields = [
            'multipart' => [
                [
                    'name' => 'first_name',
                    'contents' => $loan->getFirstName()
                ],
                [
                    'name' => 'last_name',
                    'contents' => $loan->getLastName()
                ],
                [
                    'name' => 'documentType',
                    'contents' => $loan->getDocumentType()->getId()
                ],
                [
                    'name' => '00N3600000FKIrK',
                    'contents' => $loan->getDocumentId()
                ],
                [
                    'name' => 'email',
                    'contents' => $loan->getEmail()
                ],
                [
                    'name' => 'phone',
                    'contents' => $loan->getPhoneNumber()
                ],
                [
                    'name' => '00N3600000FKJ4E',
                    'contents' => $loan->getCompany()
                ],
                [
                    'name' => '00N3600000FKJ9T',
                    'contents' => $loan->getWorkingAge()
                ],
                [
                    'name' => '00N3600000FKJ49',
                    'contents' => $loan->getSalary()
                ],
                [
                    'name' => '00N3600000FKJwQ',
                    'contents' => $loan->getHaveDiscounts() ? 'Yes' : 'No'
                ],
                [
                    'name' => '00N3600000FKK9K',
                    'contents' => $loan->getAmountDiscount() ?? '0'
                ],
                [
                    'name' => '00N3600000FKLgF',
                    'contents' => $loan->getAmountRequest()
                ],
                [
                    'name' => 'cargo',
                    'contents' => $loan->getPosition()
                ],
                [
                    'name' => '00N3600000FKIil',
                    'contents' => $origin
                ],
                [
                    'name' => 'retURL',
                    'contents' => ''
                ],
                [
                    'name' => 'status',
                    'contents' => (string)$loan->getStatus()->getId()
                ],
                [
                    'name' => 'delivery',
                    'contents' => $loan->getDelivery() ? '46' : '45'
                ],
            ]
        ];

        return $fields;
    }

    /**
     * @param Loan $loan
     * @return string
     */
    protected function getOrigin(Loan $loan): string
    {
        if ($loan->getUser()->hasAffiliate()) {
            return $loan->getUser()->getAffiliate()->getName() . ' - ' . $loan->getUser()->getFullName();
        }

        return $loan->getUser()->getFullName();
    }

}