<?php


/*La methode ci-dessous retoune un tableau contenant les coefficients de Bezout u, v 
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
La methode premierAleatoire3mod4 retoutne un nombre premier entre inf et inf+lg tel que nombre= 3 mod 4
*/
function premierAleatoire3mod4($inf, $lg): int
{

    for ($i = $inf; $i < $inf + $lg; $i++) {
        if (estPremier($i) && ($i % 4 == 3)) {
            return $i;
        }
    }
    //retourne 1 si il n'a pas trouvé 
    return 1;
}

/* 
La methode premierAleatoireAvec retourne un nombre Premier avec n 
*/
function premierAleatoireAvec($n): int
{

    for ($i = 2; $i < $n - 1; $i++) {
        if (pgcd($n, $i) == 1) {
            return $i;
        }
    }
    return 1;
}

/*
La methode choixCles permet de concevoir une clef se composant de p, q et n avec la clef privee est (p,q) et la clef publique est n
*/
function choixCles($inf, $lg)
{

    $p = premierAleatoire($inf, $lg);
    $q = premierAleatoire($p + 1, $lg);
    $n = $p * $q;
    return [$p, $q, $n];
}


/*
La methode expoModulaire calcule a^n mod m
*/
function expoModulaire($a, $n, $m)
{
    //si n>2 on essaie de deviser le puissance
    if ($n > 2) {
        $q = (int)($n / 2);
        $r = $n % 2;
        return expoModulaire(expoModulaire(expoModulaire($a, $q, $m), 2, $m) * expoModulaire($a, $r, $m), 1, $m);
    } else {
        //sinon on cacule a^n mod m
        //si a^n > m on calcule le modulo si non on retourne a^n
        return (pow($a, $n) % $m) > 0 ? (pow($a, $n) % $m) : pow($a, $n);
    }
}


/*
La methode Chiffrement permet de chiffrer un nombre a saisi en utilisant l'algorithme de Rabin
*/
function Chiffrement($n)
{
    echo ("\nSaisir le nombre à chiffrer inferieur à $n: ");
    $a = (int)fgets(STDIN);
    if ($a > $n) {
        return Chiffrement($n);
    } else {
        $c = round(pow($a, 2) % $n);
        return $c;
    }
}


/*
La methode decryptage permet de décrypter im nombre c sasie avec l'algorithme de Rabin
*/
function decryptage($p, $q, $n)
{

    echo ("\nSaisir le nombre à décrypter inferieur à $n: ");
    $c = (int)fgets(STDIN);
    if ($c > $n) {
        return decryptage($p, $q, $n);
    } else {
        $mp = expoModulaire($c, ($p + 1) / 4, $p);
        $mq = expoModulaire($c, ($q + 1) / 4, $q);
        $yp = EuclideEtendu($p, $q)[0];
        if ($yp < 0) $yp = $yp + $q;
        $yq = EuclideEtendu($p, $q)[1];
        if ($yq < 0) $yq = $yq + $p;

        $r1 = ($yp * $p * $mq) % $n + ($yq * $q * $mp) % $n;
        $r2 = $n - $r1;
        $r3 = ($yp * $p * $mq) % $n - ($yq * $q * $mp) % $n;
        $r4 = $n - $r3;
        return [$r1, $r2, $r3, $r4];
    }
}

/*
test de l'algorithme
*/
echo (Chiffrement(77));
echo (json_encode(decryptage(7, 11, 77)));
