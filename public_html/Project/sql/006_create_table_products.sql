create table if not exists `Products` (
    `id` int auto_increment not null,
    `name` varchar(75) not null unique,
    `description` varchar(500) default '',
    `category` varchar(50) not null,
    `stock` int not null default 0,
    `created` timestamp default current_timestamp,
    `modified` timestamp default current_timestamp on update current_timestamp,
    `unit_price` float (2) not null default 0.00,
    `visibility` tinyint(1) not null default 1,
    primary key (`id`)
)