             ,----------------,              ,---------,
        ,-----------------------,          ,"        ,"|
      ,"                      ,"|        ,"        ,"  |
     +-----------------------+  |      ,"        ,"    |
     |  .-----------------.  |  |     +---------+      |
     |  |                 |  |  |     | -==----'|      |
     |  |  I LOVE DOS!    |  |  |     |         |      |
     |  |  Bad command or |  |  |/----|`---=    |      |
     |  |  C:\>_          |  |  |   ,/|==== ooo |      ;
     |  |                 |  |  |  // |(((( [33]|    ,"
     |  `-----------------'  |," .;'| |((((     |  ,"
     +-----------------------+  ;;  | |         |,"     -Hericlis-
        /_)______________(_/  //'   | +---------+
   ___________________________/___  `,
  /  oooooooooooooooo  .o.  oooo /    \,"-----------
 / ==ooooooooooooooo==.o.  ooo= //    ,`\--{)B     ,"
/_==__==========__==_ooo__ooo=_/'     /___________,"
`-----------------------------' 

#Desygner Demonstration by. Hericlis Martins

> This demonstration use the <a href="https://api.imgur.com/">ImgUR API</a> to search images and later save into a database. <

@Tech specifications

[Technologies]
- PHP 7.4
- MySQL
- Symfony 5 (Web-pack, Doctrine ORM, Annotations)
- React.js (Material-ui)

[Install]
- Have the PHP installed and working well. Please, follow this instructions at Symfony page https://symfony.com/doc/current/setup.html#technical-requirements

- Have a Database which you most like.
/* You can choose the database that you prefer, the Docrine ORM care about the migration look at https://symfony.com/doc/current/doctrine.html */  

- Clone this repository, and use the command "symfony check:requirements" if needs, install the PHP extentions missing.

- Config the .env files, you could create a .env.local to config your local database. /* Look at: https://symfony.com/doc/current/configuration.html#selecting-the-active-environment */
(The file .env.desygner is the file which have the IMGUR CLIENTID API, it is my clientID. ) /*I'm not sure if this still working, if needs please register a new code here https://api.imgur.com/oauth2/addclient */


- Install all dependencies 
composer install /*Symfony*/
yarn install /*React.js*/

@Start coding

- Start the symfony server /* symfony server:start*/\

- Compile the React javascript file using the WebPack server 
yarn encore dev-server /* Auto load the front end into the broweser when you change the files*/
yarn encore dev --watch /* You will have to manually update the page to see the changes*/

/* I detected that the ImgUR  blocks the request from the IP numbers like 127.0.0.1 So to use the images show correctly you need to access from a virtual DNS name like localhost:8000 */

@Symfony Microservice API.

[API endpoints]

/api/image/InsertLibrary 
Method: POST
Param: Json {"url":"", "title":"", "description":""}
Return: 'message' => ['text' => '', 'level' => '']
Description: Insert this JSON into the DB, It is validate using the Symfony Form Component. /* ./Form/ImageType.php */

/api/image/readLibrary
Method: GET 
Param: null
Return: 'message' => ['text' => '', 'level' => '']
Description: Return the data from DB

/api/image/imgur/{max}/{keyword}
Method: GET 
Param: Max=number of images, Keyword= word to search
Return: 'message' => ['text' => '', 'level' => '']
Description: Return the data from ImgUR
