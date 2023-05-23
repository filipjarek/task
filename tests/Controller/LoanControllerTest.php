<?php

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoanControllerTest extends WebTestCase
{
    public function testIfCalculatePageWorks(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface */
        $urlGeneratorInterface = $client->getContainer()->get('router');

        $client->request(Request::METHOD_GET, $urlGeneratorInterface->generate('calculate_fee'));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCalculateFee()
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface */
        $urlGeneratorInterface = $client->getContainer()->get('router');

        $crawler = $client->request(Request::METHOD_GET, $urlGeneratorInterface->generate('calculate_fee'));

        $form = $crawler->filter('form[name=loan_form]')->form([
            'loan_form[loanAmount]' => '1000',
            'loan_form[loanTerm]' => '12'
        ]);

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Assert that the loan amount, fee, total payment, loan term, and monthly installment are displayed correctly
        $this->assertSelectorTextContains('.loanAmount', '1,000.00 PLN');
        $this->assertSelectorTextContains('.fee', '50 PLN');
        $this->assertSelectorTextContains('.totalPayment', '1,050.00 PLN');
        $this->assertSelectorTextContains('.loanTerm', '12');
        $this->assertSelectorTextContains('.monthlyInstallment', '87.50 PLN');
    }
}
