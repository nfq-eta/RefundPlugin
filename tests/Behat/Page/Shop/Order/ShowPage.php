<?php

declare(strict_types=1);

namespace Tests\Sylius\RefundPlugin\Behat\Page\Shop\Order;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Shop\Order\ShowPage as BaseOrderShowPage;

final class ShowPage extends BaseOrderShowPage implements ShowPageInterface
{
    public function countCreditMemos(): int
    {
        return count($this->getDocument()->findAll('css', '#credit-memos tbody tr'));
    }

    public function downloadCreditMemo(int $index): void
    {
        $creditMemo = $this->getCreditMemosList()[$index];
        $creditMemo->clickLink('Download');
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'credit_memos' => '#credit-memos',
        ]);
    }

    private function getCreditMemosList(): array
    {
        return $this->getElement('credit_memos')->findAll('css', 'tr');
    }
}
