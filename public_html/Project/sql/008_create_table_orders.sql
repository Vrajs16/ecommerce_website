create table if not exists `Orders` (
    `id` int auto_increment not null,
    `user_id` int not null,
    `total_price` float not null default 0.00,
    `payment_method` text,
    `created` timestamp default current_timestamp,
    `address` text,
    primary key (`id`),
    FOREIGN KEY (`user_id`) REFERENCES Users(`id`)
)