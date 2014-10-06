CREATE DATABASE if not exists jsvideos;
GRANT USAGE ON *.* to jsvideos@localhost IDENTIFIED BY 'videopass';
GRANT ALL PRIVILEGES ON jsvideos.* TO videos@localhost WITH GRANT OPTION;
FLUSH PRIVILEGES;
ALTER DATABASE videos DEFAULT CHARACTER SET latin1;
