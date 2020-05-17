# Desygner Demonstration 
### by. Hericlis Martins

------------
This demonstration use the <a href="https://api.imgur.com/">ImgUR API</a> to search images and later save into a database.


#### Tech specifications:

- PHP 7.4
- MySQL
- Symfony 5 (Web-pack, Doctrine ORM, Annotations)
- React.js (Material-ui)

#### How to:

#####[Install]

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

** /api/image/imgur/{max}/{keyword}**

	Method: GET 
	Param: Max=number of images, Keyword= word to search
	Return: 'message' => ['text' => '', 'level' => '']
	Description: Return the data from ImgUR


### Test:
In order to learn how to use the symfony framework, I performed a single unit test on the API using the PHPUnit component. 
*This test makes a request to the endpoint  `/api/image/readLibrary`*

**./tests/ImageTest.php**

	class ImageTest extends TestCase

**Start the test**:` php ./bin/phpunit ./tests/ImageTest.php`

**Faking a failure: ** There are two assertions response in this test model.

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

- front-end needs to improve creating alerts and notifications with the material-ui.
- Back-end implement a Cache to the ImgUR returns.

#### References:

https://symfony.com/
https://phpunit.readthedocs.io/en/9.1/
https://api.imgur.com/
https://reactjs.org/
https://webpack.js.org/

