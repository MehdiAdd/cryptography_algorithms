<?php
/*La methode ci-dessus retoune un tableau contenant les coefficients de Bezout u, v 
et le pgcd d de deux nombre a et b tels que au + bv = d 
*/
function EuclideEtendu($a, $b)
{
    $d = $a;
    $u = 1;
    if ($b == 0) {
        $v = 0;
        return [$u, $v, $d];
    } else {
        $u1 = 0;
        $d1 = $b;
    }
    while (1) {
        if ($d1 == 0) {
            $v = (int)(($d - $a * $u) / $b);
            return [$u, $v, $d];
        }
        $q = (int) ($d / $d1);
        $d2 = $d % $d1;
        $u2 = $u - ($q * $u1);
        $u = $u1;
        $d = $d1;
        $u1 = $u2;
        $d1 = $d2;
    }
}

/*
La methode CalculeInverse permet de calculer l'inverse d'un nombre b modulo a 
en utilisant la fonction EuclideEtundu definie ci-dessus
*/
function CalculeInverse($a, $b)
{
    return EuclideEtendu($a, $b)[1];
}


/*
Calcule de l'inverse de 5 modulo 6
*/
$a = 6;
$b = 5;
echo ("L'inverse de $b modulo $a est " . CalculeInverse($a, $b));
