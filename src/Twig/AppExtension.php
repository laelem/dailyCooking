<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('remainingDate', [$this, 'remainingDate']),
        ];
    }

    public function remainingDate(DateTime $date): string
    {
        $diff = (new DateTime())->setTime(0, 0)->diff($date->setTime(0, 0));

        if (0 === $diff->days) {
            return "Périme aujourd'hui";
        }

        if (1 === $diff->invert && $diff->days > 7) {
            return "Périmé(e) depuis plus d'une semaine";
        }

        if ($diff->days <= 30) {
            return 0 === $diff->invert ?
                sprintf('Périme dans %s jour%s', $diff->days, ($diff->days > 1 ? 's' : '')) :
                sprintf('Périmé(e) depuis %s jour%s', $diff->days, ($diff->days > 1 ? 's' : ''));
        }

        if ($diff->days >= 365) {
            return "Périme dans plus d'un an";
        }

        return sprintf('Périme dans %s mois', floor($diff->days/30));
    }
}