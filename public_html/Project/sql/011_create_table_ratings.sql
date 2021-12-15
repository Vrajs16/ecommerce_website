CREATE TABLE IF NOT EXISTS Ratings(
    id INT AUTO_INCREMENT NOT NULL,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT NOT NULL,
    created timestamp default current_timestamp,
    PRIMARY KEY(id),
    FOREIGN KEY(product_id) REFERENCES Products(id),
    FOREIGN KEY(user_id) REFERENCES Users(id)
)