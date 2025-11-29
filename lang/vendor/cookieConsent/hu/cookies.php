<?php
return [
    'title' => 'Cookiekat használunk',
    'intro' => 'Ez a weboldal sütiket használ a felhasználói élmény javítása érdekében.',
    'link' => 'További információkért nézz rá a <a href=":url">cookie tájékoztatónkra</a>.',

    'essentials' => 'Nélkülözhetetlenek elfogadása',
    'all' => 'Összes elfogadása',
    'customize' => 'Testreszabás',
    'manage' => 'Cookiek kezelése',
    'details' => [
        'more' => 'Több részlet',
        'less' => 'Kevesebb részlet',
    ],
    'save' => 'Mentés',
    'cookie' => 'Cookie',
    'purpose' => 'Cél',
    'duration' => 'Időtartam',
    'year' => 'év|év',
    'day' => 'nap|nap',
    'hour' => 'óra|óra',
    'minute' => 'perc|perc',

    'categories' => [
        'essentials' => [
            'title' => 'Nélkülözhetetlen cookiek',
            'description' => 'Vannak cookiek, amelyeket be kell építenünk ahhoz, hogy bizonyos funkciók rendeltetésszerűen működjenek. Emiatt nem igénylik a külön hozzájárulásodat.',
        ],
        'analytics' => [
            'title' => 'Analitikai cookiek',
            'description' => 'Ezeket belső kutatáshoz használjuk, hogy hogyan tudjuk javítani a felhasználóink számára nyújtott szolgáltatásunkat. Ezek a cookiek azt mérik fel, hogy hogyan használod a weboldalunkat.',
        ],
        'optional' => [
            'title' => 'Opcionális cookiek',
            'description' => 'Ezek a cookiek olyan funkciókat tesznek lehetővé, amelyek javíthatják a felhasználói élményt, de hiányuk nem befolyásolja a weboldalunk böngészésének lehetőségét.',
        ],
    ],

    'defaults' => [
        'consent' => 'A felhasználó cookiehasználattal kapcsolatos hozzájárulásának tárolására szolgál.',
        'session' => 'A felhasználó böngészési munkamenetének azonosítására szolgál.',
        'csrf' => 'A felhasználó és weboldalunk védelmére szolgál a webhelyek közötti támadások (cross-site request forgery) ellen.',
        '_ga' => 'A Google Analytics által használt fő cookie, ami lehetővé teszi a szolgáltatás számára, hogy megkülönböztesse a látogatókat a többitől.',
        '_ga_ID' => 'A Google Analytics használja a munkamenet állapotának megőrzésére.',
        '_gid' => 'A Google Analytics használja a felhasználó azonosítására.',
        '_gat' => 'A Google Analytics használja a kérések gyakoriságának szabályozására.',
    ],
];
