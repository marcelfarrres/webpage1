# TODO 1rst srpint (create a functional flow)

- [x] Public Pages
    - [x] Landing Page (Marcel)
    - [x] Sign-up (Marcel)
    - [x] Sign-in (Marcel)

- [ ] Middleware
    - [ ] AuthenticationMiddleware: Profile and ... cannot be acccessed by unisgned Users
       |---[x] General Structure working.
       |---[ ] How can I create a response in other way.

    - [ ] FirstTimeMiddleware: When an user accesses any private page without having set their   username, they are redirected to the profile page with a flash message

- [ ] CSS
    - [x] Create a general CSS for all views
    - [x] button class and map all buttons views 

- [ ] EXTERNAL APIS-Repositories //TODO: How to distribute the api calls and the Database calls
    - [x] UserRepository
    - [ ] BookRepository


- [ ] Private Pages
    - [ ] Profile (Marcel)
       |---[x] Main Structure and modifing the Database 
       |---[x] ProfileController: handle Image Error
       |---[x] ProfileController - Profile.twig: Username not showing after updating. 
       |---[x] ProfileController: check if username is unique
       |---[ ] ProfileController: how to persist the image
    
    - [ ] Catalogue (Marcel)
       |---[x] Main Structure and routing
       |---[ ] Forms distribution

    - [ ] Book Details
    - [ ] Book Ratings and Reviews
    - [ ] Discussion Forums
    - [ ] Forum Posts
    - [ ] Discussion Forums

- [ ] Extra Features
    - [ ] Navigation Menu Sidebar
    - [ ] Discussion Forums (API)

- [ ] Delivery 
    - [ ] include INSTRUCTIONS.md