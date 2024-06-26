<?php

declare(strict_types=1);

namespace Genkgo\Camt\Camt054\Decoder;

use Genkgo\Camt\Camt054\DTO as Camt054DTO;
use Genkgo\Camt\Decoder\Message as BaseMessageDecoder;
use Genkgo\Camt\DTO;
use Genkgo\Camt\DTO\Account;
use Genkgo\Camt\Exception\InvalidMessageException;
use Genkgo\Camt\Iban;
use SimpleXMLElement;

class Message extends BaseMessageDecoder
{
    public function addRecords(DTO\Message $message, SimpleXMLElement $document): void
    {
        $notifications = [];

        $xmlNotifications = $this->getRootElement($document)->Ntfctn;
        foreach ($xmlNotifications as $xmlNotification) {
            $notification = new Camt054DTO\Notification(
                (string) $xmlNotification->Id,
                $this->dateDecoder->decode((string) $xmlNotification->CreDtTm),
                $this->getAccount($xmlNotification)
            );

            if (isset($xmlNotification->NtfctnPgntn)) {
                $notification->setPagination(new DTO\Pagination(
                    (string) $xmlNotification->NtfctnPgntn->PgNb,
                    ('true' === (string) $xmlNotification->NtfctnPgntn->LastPgInd) ? true : false
                ));
            }

            if (isset($xmlNotification->AddtlNtfctnInf)) {
                $notification->setAdditionalInformation((string) $xmlNotification->AddtlNtfctnInf);
            }

            $this->addCommonRecordInformation($notification, $xmlNotification);
            $this->recordDecoder->addEntries($notification, $xmlNotification);

            $notifications[] = $notification;
        }

        $message->setRecords($notifications);
    }

    /**
     * @inheritDoc
     */
    public function getRootElement(SimpleXMLElement $document): SimpleXMLElement
    {
        return $document->BkToCstmrDbtCdtNtfctn;
    }

    protected function getAccount(SimpleXMLElement $xmlRecord): Account
    {
        $account = null;
        if (isset($xmlRecord->Acct->Id->IBAN)) {
            $account = new DTO\IbanAccount(new Iban((string) $xmlRecord->Acct->Id->IBAN));
        } elseif (isset($xmlRecord->Acct->Id->BBAN)) {
            $account = new DTO\BBANAccount((string) $xmlRecord->Acct->Id->BBAN);
        } elseif (isset($xmlRecord->Acct->Id->UPIC)) {
            $account = new DTO\UPICAccount((string) $xmlRecord->Acct->Id->UPIC);
        } elseif (isset($xmlRecord->Acct->Id->PrtryAcct)) {
            $account = new DTO\ProprietaryAccount((string) $xmlRecord->Acct->Id->PrtryAcct->Id);
        } elseif (isset($xmlRecord->Acct->Id->Othr)) {
            $xmlOtherIdentification = $xmlRecord->Acct->Id->Othr;
            $account = new DTO\OtherAccount((string) $xmlOtherIdentification->Id);

            if (isset($xmlOtherIdentification->SchmeNm)) {
                if (isset($xmlOtherIdentification->SchmeNm->Cd)) {
                    $account->setSchemeName((string) $xmlOtherIdentification->SchmeNm->Cd);
                }

                if (isset($xmlOtherIdentification->SchmeNm->Prtry)) {
                    $account->setSchemeName((string) $xmlOtherIdentification->SchmeNm->Prtry);
                }
            }

            if (isset($xmlOtherIdentification->Issr)) {
                $account->setIssuer((string) $xmlOtherIdentification->Issr);
            }
        }

        if ($account instanceof DTO\Account) {
            if ($Ownr = data_get($xmlRecord, 'Acct.Ownr')) {
                $this->accountAddOwnerInfo($account, $Ownr);
            }
            if ($Svcr = data_get($xmlRecord, 'Acct.Svcr')) {
                $this->accountAddServicerInfo($account, $Svcr);
            }
            if ($Ccy = data_get($xmlRecord, 'Acct.Ccy')) {
                $account->setCurrency(new \Money\Currency((string) $Ccy));
            }

            return $account;
        }

        throw new InvalidMessageException('Cannot decode account');
    }
}
