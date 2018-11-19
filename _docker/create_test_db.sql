# Create thinkblog_test database if it doesn't exist
CREATE DATABASE IF NOT EXISTS thinkblog_test;

# Grant all privilidges on thinkblog_test to root
-- GRANT ALL PRIVILEGES ON thinkblog_test.* TO 'root' identified by 'P@55w0rd';
GRANT ALL PRIVILEGES ON thinkblog_test.* TO 'root'@'localhost';
