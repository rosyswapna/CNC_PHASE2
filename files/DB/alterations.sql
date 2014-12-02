ALTER TABLE `users` ADD UNIQUE(`username`);
ALTER TABLE `users` ADD UNIQUE( `email`, `user_type_id`);
ALTER TABLE `users` ADD UNIQUE( `phone`, `user_type_id`);


