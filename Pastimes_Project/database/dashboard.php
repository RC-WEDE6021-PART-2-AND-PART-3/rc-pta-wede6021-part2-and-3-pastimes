CREATE DATABASE IF NOT EXISTS pastimes_db;
USE pastimes_db;

CREATE TABLE users (
  user_id int(11) NOT NULL AUTO_INCREMENT,
  full_name varchar(100) NOT NULL,
  email varchar(100) NOT NULL UNIQUE,
  username varchar(50) NOT NULL UNIQUE,
  password_hash varchar(255) NOT NULL,
  role enum('buyer','seller','admin') DEFAULT 'buyer',
  is_verified_seller tinyint(1) DEFAULT 0,
  address text,
  phone varchar(20),
  created_at timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (user_id)
);

INSERT INTO users (full_name, email, username, password_hash, role, is_verified_seller) VALUES
('Admin User', 'admin@pastimes.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 0);

CREATE TABLE products (
  product_id int(11) NOT NULL AUTO_INCREMENT,
  seller_id int(11) NOT NULL,
  title varchar(150) NOT NULL,
  description text,
  price decimal(10,2) NOT NULL,
  category varchar(50),
  size varchar(10),
  `condition` enum('New with tags','Like new','Good','Fair') DEFAULT 'Good',
  status enum('available','sold','removed') DEFAULT 'available',
  image_path varchar(255),
  created_at timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (product_id),
  FOREIGN KEY (seller_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE messages (
  message_id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(11),
  sender_id int(11) NOT NULL,
  receiver_id int(11) NOT NULL,
  subject varchar(200),
  body text,
  is_read tinyint(1) DEFAULT 0,
  sent_at timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (message_id),
  FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (receiver_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE orders (
  order_id int(11) NOT NULL AUTO_INCREMENT,
  buyer_id int(11) NOT NULL,
  total_amount decimal(10,2) NOT NULL,
  order_date timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (order_id),
  FOREIGN KEY (buyer_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE order_items (
  item_id int(11) NOT NULL AUTO_INCREMENT,
  order_id int(11) NOT NULL,
  product_id int(11) NOT NULL,
  quantity int(11) NOT NULL,
  price_at_purchase decimal(10,2) NOT NULL,
  PRIMARY KEY (item_id),
  FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);