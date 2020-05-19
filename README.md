# Desygner Demonstration 
### by. Hericlis Martins

------------
This demonstration use the <a href="https://api.imgur.com/">ImgUR API</a> to search images and later save into a database.

------------
#### News Features:

**APCU Cache:**
I have implemented the cache using the APCU extention, using the Component Cache\Adapter\ApcuAdapter from symfony. *
> Now it is impresendive do you include and enable the APCU extention in your PHP.  Have a look at: https://www.php.net/manual/en/apcu.installation.php

Commit reference: [#cca03cb](https://github.com/HericlisMartins/desygnerDemo/commit/cca03cb9df557c96b81759d3c1f42af9784d1e04 "#cca03cb")

**New endpoint of the ImageUR API:**

	/api/image/imgur/{*maxofImgs*}/**{cache state}**/{*keyword*}

**Cache states:**

		1 - Not use at all
		0  - Try to get from cache if not exists get the imgur json and save
		1  - Only accepts values from the cache.


**Validation:**
Now the keyword received into the API endpoint */api/image/imgur/* is valitating using the Symfony\Component\Validator\ 

**Commit reference:** [#3fd1067](https://github.com/HericlisMartins/desygnerDemo/commit/3fd1067977f47d43123c4ecd3391b0158a9778c5 "#3fd1067")

**Validation constraints:**
- Regex  (https://symfony.com/doc/current/reference/constraints/Regex.html)
- Length (https://symfony.com/doc/current/reference/constraints/Length.html)


------------

#### Tech specifications:

- PHP 7.4
- MySQL
- Symfony 5
- React.js (Material-ui)

#### How to:

##### [Install]

- Have the PHP installed and working well. Please, follow this instructions at Symfony page https://symfony.com/doc/current/setup.html#technical-requirements

- Have a Database which you most like. 
*You can choose the database that you prefer, the Docrine ORM care about the migration look at https://symfony.com/doc/current/doctrine.html*

- Clone this.  `git clone git@github.com:HericlisMartins/desygnerDemo.git`

- Check the dependencies from @Symfony `symfony check:requirements` 
*if needs, install the PHP extentions missing.*

- Config the .env file
*You could create a .env.local to config your local database. Look at: https://symfony.com/doc/current/configuration.html#selecting-the-active-environment *
> The file .env.desygner is the file which have the IMGUR CLIENTID API, it is my clientID. I'm not sure if this still working, if needs please register a new code here https://api.imgur.com/oauth2/addclient

- Install all dependencies
`composer install` Back-end dependencies
`yarn install` Front-end dependencies

##### [Using]

- Start the symfony server `symfony server:start`

- Compile the React javascript file using the WebPack encore

	`yarn encore dev-server` *Auto load the front end into the broweser when you change the files*

	`yarn encore dev --watch`  *You will have to manually update the page to see the changes*

> :tw-2757: I have detected that the imgur  blocks the request from the ip numbers like 127.0.0.1 so to use the images show correctly you need to access from a virtual dns name like localhost:8000


##### @Symfony Microservice API.

###### [API endpoints]
**/api/image/insertlibrary**

	Method: POST
	Param: Json {"url":"", "title":"", "description":""}
	Return: 'message' => ['text' => '', 'level' => '']
	Description: Insert this JSON into the DB, It is validate using the Symfony Form Component.  `./Form/ImageType.php `

**/api/image/readLibrary**

	Method: GET 
	Param: null
	Return: 'message' => ['text' => '', 'level' => '']
	Description: Return the data from DB

**/api/image/imgur/{max}/{cache state}/{keyword}**

	Method: GET 
	Param: Max=number of images, Keyword= word to search, Cache State
	Return: 'message' => ['text' => '', 'level' => '']
	Description: Return the data from ImgUR


### Test:
In order to learn how to use the symfony framework, I performed a single unit test on the API using the PHPUnit component. 
*This test makes a request to the endpoint  `/api/image/readLibrary`*

**./tests/ImageTest.php**

	class ImageTest extends TestCase

**Start the test**:` php ./bin/phpunit ./tests/ImageTest.php`

**Faking a failure:** 
There are two assertions response in this test model.

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("application/json", $headers["content-type"][0]);

To fake a failure test just change the type of the return:

**./Controller/ImageController.php**

        $response = new Response(
            json_encode($arrayOfimgs),
            Response::HTTP_OK,
            ['content-type' => 'text/html']   < Uncomment this line
           // ['content-type' => 'application/json']  < Comment this line
        );


# Future Features and Final  Considerations.

It was amazing lern the @Symfony Framework developing a demonstration for this interview.
I hope that this code meets the expectations of an interviewer and that this makes me possibly more likely to be hired.
On the other hand I really enjoyed working with @Symfony, and I will probably use this again.

**Improvements**:

- ~~Back-end implement a Cache to the ImgUR returns.~~
- Front-end (Create the controllers to interact with the cache, create alerts from return)


#### References:

- https://symfony.com/
- https://phpunit.readthedocs.io/en/9.1/
- https://api.imgur.com/
- https://reactjs.org/
- https://webpack.js.org/

