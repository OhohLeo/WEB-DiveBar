CREATE TABLE livredor(
   code int(11) NOT NULL auto_increment,
   date varchar(20) NOT NULL,
   nom varchar(50),
   email varchar(50),
   commentaire blob,
   PRIMARY KEY (code)
);

