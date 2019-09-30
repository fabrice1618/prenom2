<?php

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PWD",  "");
define("DB_BASE", "prenom2");

$dbLink = mysqli_connect(DB_HOST, DB_USER, DB_PWD) or die("Impossible de se connecter : " . mysqli_error());
if (mysqli_select_db($dbLink, DB_BASE)) {
    truncateTable("prenom");

    loadPrenom();
}

mysqli_close($dbLink);


// Functions
function loadPrenom()
{
    global $dbLink;

    echo "LoadPrenom\n";
    $fp = fopen("dpt2017.txt", "r");
    while ( ($sLine = fgets($fp, 4096)) !== false) {
            //echo $sLine;
            list($sSexe, $sPrenom, $sAnnee, $sDepart, $sNombre) = explode("\t", $sLine, 5);

            $sQueryTemplate = 'INSERT INTO prenom (`sexe`, `prenom`, `annee`, `dept`, `nombre`) VALUES ("%s", "%s", "%s", "%s", "%s")';
            $sQuery = sprintf($sQueryTemplate, $sSexe, $sPrenom, $sAnnee, $sDepart, intval($sNombre) );
            echo $sQuery . "\n";
            if ($dbLink->query($sQuery) === TRUE) {
                printf("Requete OK\n");
            }
    }

    fclose($fp);
}

function truncateTable($sTable)
{
    global $dbLink;

    $sQueryTemplate = "TRUNCATE TABLE %s";

    if (!mysqli_query($dbLink, sprintf($sQueryTemplate, $sTable))) {
           echo sprintf("Erreur : %s\n", mysqli_error($dbLink));
    } else {
        echo "Table $sTable truncated\n";
    }
}
