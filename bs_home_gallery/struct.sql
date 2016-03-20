CREATE TABLE /*TABLE_PREFIX*/bs_home_gallery (
  bs_key_id int(11) NOT NULL,
  s_title varchar(255) NOT NULL,
  s_description text NOT NULL,
  s_image varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE /*TABLE_PREFIX*/bs_home_gallery
  ADD PRIMARY KEY (bs_key_id);
ALTER TABLE /*TABLE_PREFIX*/bs_home_gallery
  MODIFY bs_key_id int(11) NOT NULL AUTO_INCREMENT;
