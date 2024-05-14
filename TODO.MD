# TODO 1rst srpint (create a functional flow)

- [x] Public Pages
    - [x] Landing Page 
    - [x] Sign-up 
    - [x] Sign-in 

- [ ] Middleware
    - [ ] AuthenticationMiddleware: Profile and ... cannot be acccessed by unisgned Users
       |---[x] General Structure working.
       |---[x] How can I create a response in other way.

    - [ ] FirstTimeMiddleware: When an user accesses any private page without having set their   username, they are redirected to the profile page with a flash message

- [ ] CSS
    - [x] Create a general CSS for all views
    - [x] button class and map all buttons views 

- [ ] EXTERNAL APIS-Repositories //TODO: How to distribute the api calls and the Database calls
    - [x] UserRepository
    - [x] BookRepository
    - [ ] GuzzleService


- [ ] Private Pages
    - [ ] Profile 
       |---[x] Main Structure and modifing the Database 
       |---[x] ProfileController: handle Image Error
       |---[x] ProfileController - Profile.twig: Username not showing after updating. 
       |---[x] ProfileController: check if username is unique
       |---[x] ProfileController: how to persist the image
    
    - [ ] Catalogue 
       |---[x] Main Structure and routing
       |---[x] Create and connect the BookRepository
       |---[x] Forms distribution
       |---[x] Implement the BookService created

    - [ ] Book Details
       |---[x] Hacer lo de /catalogue/id
       
    - [ ] Book Ratings and Reviews
        |---[x] Add and get
        |---[ ] Delete Rating
        |---[ ] Delete Review

    - [ ] Discussion Forums
    - [ ] Forum Posts

- [ ] Extra Features
    - [x] Navigation Menu Sidebar
    - [ ] Discussion Forums (API)

- [ ] Delivery 
    - [ ] include INSTRUCTIONS.md
