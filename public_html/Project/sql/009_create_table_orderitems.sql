create table if not exists `OrderItems` (
    `id` int auto_increment not null,
    `product_id` int not null,
    `order_id` int not null,
    `quantity` int not null default 0,
    `unit_price` float (2) not null default 0.00,
    primary key (`id`),
    FOREIGN KEY (`product_id`) REFERENCES Products(`id`),
    FOREIGN Key (`order_id`) REFERENCES Orders(`id`)
)