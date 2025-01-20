CREATE TABLE IF NOT EXISTS "prefix_orders" (
    id SERIAL PRIMARY KEY,
    trade_no VARCHAR(64) UNIQUE NOT NULL,
    api_trade_no VARCHAR(64),
    name VARCHAR(64) NOT NULL,
    content_title VARCHAR(150),
    content_cid INT NOT NULL,
    "type" VARCHAR(10) NOT NULL,
    money VARCHAR(32) NOT NULL,
    ip VARCHAR(32),
    user_id VARCHAR(32) NOT NULL,
    create_time TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
    update_time TIMESTAMP WITHOUT TIME ZONE,
    pay_type VARCHAR(10),
    pay_price VARCHAR(32),
    admin_email BOOLEAN NOT NULL DEFAULT FALSE,
    user_email BOOLEAN NOT NULL DEFAULT FALSE,
    "status" BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS "prefix_friends" (
    id SERIAL PRIMARY KEY,
    title VARCHAR(128) NOT NULL,
    url VARCHAR(255) NOT NULL,
    description TEXT,
    logo TEXT,
    rel VARCHAR(128),
    email VARCHAR(64),
    "order" INT NOT NULL DEFAULT 0,
    "status" BOOLEAN NOT NULL DEFAULT FALSE,
    create_time TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
);