create table if not exists `Cart` (
    `id` int auto_increment not null,
    `product_id` int not null,
    `user_id` int not null,
    `desired_quantity` int not null default 0,
    `unit_cost` float (2) not null default 0.00,
    `created` timestamp default current_timestamp,
    `modified` timestamp default current_timestamp on update current_timestamp,
    primary key (`id`),
    FOREIGN KEY (`product_id`) REFERENCES Products(`id`)
)