<?php

namespace App\Entity;

enum CommandeStatut: string
{
    case EN_ATTENTE = 'en_attente';
    case PAYEE = 'payee';
}
