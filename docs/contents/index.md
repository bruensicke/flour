# Say Hello to Contents in Flour

Contents aka ContentLibrary is a database of any data you can think of. It can store any type of data with unlimited nesting and is therefore schema-free.

Because it respects the Flour Scheduling mechanism, you can just get an image, an introduction-text or both at once.

Use it like this:

	//gets a complete image
	echo $this->Content->get('logo');
	
	//gets a whole form
	echo $this->Content->get('registration_form');
	

