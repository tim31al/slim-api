<?php

use DI\ContainerBuilder;

require_once __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(
    __DIR__ . '/../config/settings.php',
    __DIR__.'/../config/settings.local.php'
);
$builder->addDefinitions(__DIR__.'/../config/services.php');

$c = $builder->build();

$dbh = $c->get(PDO::class);


$sql = 'DROP TABLE IF EXISTS products';

$dbh->exec($sql);


$sql = 'create table products
(
    id int auto_increment  primary key,
    name varchar(100) not null,
    description text  not null,
    price float default 0 null,
    created_at datetime not null,
    constraint products_name_udx
        unique (name)
)';

$dbh->exec($sql);


for($i = 1; $i <= 5; $i++) {
    $sql = sprintf(
        'insert into products (name, description, price, created_at) values ("%s", "%s", %.2f, "%s")',
        'Product '.$i,
        'Description product '.$i,
        rand(1, 100) + rand(1, 9) / 10,
        date('Y-m-d H:m:s', time())
    );
    $dbh->exec($sql);
}
