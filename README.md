# Tic Tac Toe

## Requirements

- docker
- docker-compose
- make

## Installation 

> make install

## Tests

> make tests
 
## App execution - Symfony Command

Execute the following command, and you will be able to play the game reading the cli messages.


> make app

Consider the happy scenario:
- create user for player 1
- create user for player 2
- start game setting the generated player ids.
- first player will be randomly selected
- next user id will be displayed as a message
- introduce the board positions choosing from 0 to 8

### Board positions:

|   |   |   |
|---|---|---|
|  0 | 1  | 2  |
|  3 | 4  | 5  |
|  6 | 7  | 8  |


## Decisions made

1. I decided to follow DDD and Hexagonal architecture 
   - Decided to separate app in 2 context. Users and Games.
   
2. I followed TDD, first unit testing and then code
3. Persistence is provisionally in memory. Until we decide what database use ;) 
4. I expected to add functional tests, but I run out of time doing the cli command playable.
   Considering simplicity of project and considering persistence is provisionally saved in memory, It's okey not to have functional tests, for now.
   (I could show you other projects I made with functional tests.)
5. I separated source code (`src/`) and apps code (`apps/tic-tac-toe`)
6. I used php 8 and symfony 5. The last versions for each one.