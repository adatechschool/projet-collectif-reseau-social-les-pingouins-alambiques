First Day : Issues installing XAMPP on Bradley and Quentin's computers but the MAMP stack worked straight away on the machines 
of Myriam and Eline.

Second Day : Finished the configuration and installation of MAMP on the machines of Quentin and Bradley. In the meantime Myriam 
and Eline had advanced on the project and had resolved several of the @todo items.

Third Day: Finished the Niveau_1 and refactored as we deemed necessary/useful.

Fourth Day : Blocked with the registration of a new entry into the database/users table. With the help of Nicolas we examined the 
server logs and discovered the error with named variables not being referenced properly after they are defined (alias and pseudo).

Fifth Day : We have progressed well, the follow button works and we have a message in place when you have already followed somone. 
Now looking at how the likes function and are organised in relation to the database we have stumbled onto a bug where the number of likes 
is multiplied by the number of tags a post has...which obviously is not ideal! Looking at the SQL request there is no obvious error...
...but still.

Sixth Day(Final Day of Reckoning!!) : So we split into 2 pairs so that Brad and Quentin can work on the like button functionality and Eline 
and Myriam can work on the CSS and style the reseau-social. Quentin has solved/put in place the function for adding a like to the database, 
but after liking a post, each time the page is refreshed the number of likes increments by one, however no additional entries are inserted 
into the database.....puzzling.