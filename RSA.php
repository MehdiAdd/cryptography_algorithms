<?php

function pgcd($a, $b): int
{
    $reste = $a % $b;        // 5=3*1+2   3=2*1+1  2=1*2+0
    $a = $b;
    $b = $reste;
    if ($b == 0) return $a;
    else return pgcd($a, $b);
}/*
echo "Calcul du pgcd de a et b\n";
echo "saisir a : \n";
$a = fgets(STDIN);
echo "saisir b : \n";
$b = fgets(STDIN);
echo "le pgcd de a et b est : " . pgcd($a, $b);

*/

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
La methode inverseModulaire calcule l'inverse de e modulo m avec un extra test en s'assurant que :
    1- e et m sont premiers entre eux
    2-  0 <d <m-1
*/
function inverseModulaire($e, $m)
{
    //on teste si e et m sont premiers entre eux i.e. si pgcd(m,e)=1 donc le e et m sont premiers entre eux 
    if (pgcd($m, $e) != 1) return;
    //on calcule l'inverse de e modulo m
    $d = CalculeInverse($m, $e);
    //si d<0 on rajoute m parceque l'inverse de e modulo m est d+km  (k appart. à Z)
    while ($d < 0) {
        $d += $m;
    }
    return $d;
}
/*
La methode estPremier verifie pour un nombre si il est premier ou pas
*/
function estPremier($number)
{
    // on teste si le nombre est egale à 2 donc il est premier
    if ($number  == 2) return true;
    // on teste si le nombre est devisible par 2 (paire). si oui donc il n'est pas premier (pour cette raison on a faire avancer au-dessus le test avec 2) 
    if (($number % 2) == 0) return false;
    //on va tester dès maintenant tous les nombres impaires si ils sont des déviseurs de notre nombre (puisque on a testé avec les nombres paires )
    //il y a une astuce qui nous permet de test jusuqu'à la racine carré du nombre 
    //on calcule la racine carré du nombre
    $sqrt = (int)(sqrt($number));
    //on teste tous les nombres impaires inferireurs à la racine carré du nombre si ils sont des déviseurs de notre nombre
    for ($i = 3; $i <= $sqrt; $i += 2) {
        if (($number % $i) == 0) return false;
    }
    return true;
}

/*
La methode premierAleatoire retoutne un nombre premier entre inf et inf+lg
*/
function premierAleatoire($inf, $lg): int
{

    for ($i = $inf; $i < $inf + $lg; $i++) {
        if (estPremier($i)) {
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
La methode choixCle permet de concevoir une cle se composant de p, q et e
*/
function choixCle($inf, $lg)
{

    $p = premierAleatoire($inf, $lg);
    $q = premierAleatoire($p + 1, $lg);
    $e = premierAleatoireAvec(($p - 1) * ($q - 1));
    return [$p, $q, $e];
}

/*
La methode clePubluque permet de generer la cle publique (n,e) de RSA en lui donnant p, q et e
 */
function clePubluque($p, $q, $e)
{
    $n = $p * $q;
    return [$n, $e];
}

/*
La methode clePrivee permet de generer la cle privée (n,d) de RSA en lui donnant p, q et e
 */
function clePrivee($p, $q, $e)
{
    $n = $p * $q;
    $phi = ($p - 1) * ($q - 1);
    //on obtient d en calculant l'inverse de e mod phi
    $d = inverseModulaire($e, $phi);
    return [$n, $d];
}

/*
La methode  codageRSA permet de chiffrer un message M donné avec la cle publique (n,e)
*/
function codageRSA($M, $clePublique)
{
    $cipher = expoModulaire($M, $clePublique[1], $clePublique[0]);
    return $cipher;
}

/*
La methode  decodageRSA permet de déchiffrer un message M donné avec la cle privée (n,d)
*/
function decodageRSA($M, $clePrivee)
{
    $plain = expoModulaire($M, $clePrivee[1], $clePrivee[0]);
    return $plain;
}

/*
test des fonctions
*/

echo (codageRSA(4, clePubluque(3, 11, 3)));  //cela chiffre le message '4' et retourne '31'
echo (decodageRSA(31, clePrivee(3, 11, 3))); //cela dechiffre le message '31' et retourne '4'
