--  This SQL code creates the database, creates the tables with the necessary
--  relationships, and then inserts the sample data.

-- Create the CineViews database
CREATE DATABASE IF NOT EXISTS CineViews;

-- Use the CineViews database
USE CineViews;

-- Create the Movies table
CREATE TABLE IF NOT EXISTS Movies (
    MovieId INT AUTO_INCREMENT PRIMARY KEY,
    MovieTitle VARCHAR(100) NOT NULL,
    ReleaseDate DATE NOT NULL,
    Art VARCHAR(255) NOT NULL,
    IsSeries TINYINT NOT NULL
);

-- Create the Users table
CREATE TABLE IF NOT EXISTS Users (
    UserId INT AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(50) NOT NULL,
    UserEmail VARCHAR(50),
    PasswordHash VARCHAR(100),
    ResetToken VARCHAR(50),
    ResetTokenExpiry DATETIME
);

-- Create the Genres table
CREATE TABLE IF NOT EXISTS Genres (
    GenreId INT AUTO_INCREMENT PRIMARY KEY,
    GenreName VARCHAR(50) NOT NULL
);

-- Create the MovieGenre table
CREATE TABLE IF NOT EXISTS MovieGenre (
    MovieId INT NOT NULL,
    GenreId INT NOT NULL,
    PRIMARY KEY (MovieId, GenreId),
    FOREIGN KEY (MovieId) REFERENCES Movies(MovieId) ON DELETE CASCADE,
    FOREIGN KEY (GenreId) REFERENCES Genres(GenreId) ON DELETE CASCADE
);

-- Create the Reviews table
CREATE TABLE IF NOT EXISTS Reviews (
    ReviewId INT AUTO_INCREMENT PRIMARY KEY,
    MovieId INT NOT NULL,
    UserId INT NOT NULL,
    Score FLOAT(3,1) NOT NULL,
    ReviewText TEXT NOT NULL,
    FOREIGN KEY (MovieId) REFERENCES Movies(MovieId) ON DELETE CASCADE,
    FOREIGN KEY (UserId) REFERENCES Users(UserId) ON DELETE CASCADE
);

-- Insert data to Movies table
INSERT INTO Movies (MovieId, MovieTitle, ReleaseDate, Art, IsSeries)
VALUES
    (4, 'The Witch', '2016-03-03',
   'The Witch', 0),
    (5, 'The Big Lebowski', '1998-03-06',
    'The Big Lebowski', 0),
    (6, 'Forrest Gump', '1994-07-06',
    'Forrest Gump', 0),
    (7, 'The Royal Tenenbaums', '2001-12-14',
    'The Royal Tenenbaums', 0),
    (8, 'Wild Hogs', '2007-02-27',
    'Wild Hogs', 0);

-- Insert data to Users table
-- These users don't have passwords and won't be able to actually log in to the website.
-- They are only added for sample data.
-- INSERT INTO Users (UserId, UserName)
-- VALUES
--     (1, 'Daniel B'),
--     (2, 'Alain M'),
--     (3, 'Nicolas F'),
--     (4, 'Alec C'),
--     (5, 'S. C'),
--     (6, 'Tyler M'),
--     (7, 'Patricio G'),
--     (8, 'Eric B'),
--     (9, 'Erik B'),
--     (10, 'Blu B'),
--     (11, 'Ava S'),
--     (12, 'Blob 9'),
--     (13, 'James B'),
--     (14, 'Jonathan M'),
--     (15, 'Alex C'),
--     (16, 'Dave C'),
--     (17, 'Johnathon W'),
--     (18, 'David C'),
--     (19, 'Jens B'),
--     (20, 'David I'),
--     (21, 'Ahna R'),
--     (22, 'Larry I'),
--     (23, 'Dinos K'),
--     (24, 'James L'),
--     (25, 'Mark K');

-- Insert data to Genres table
INSERT INTO Genres (GenreId, GenreName)
VALUES
    (1, 'Comedy'),
    (2, 'Action'),
    (3, 'Horror'),
    (4, 'Independent'),
    (5, 'Adventure'),
    (6, 'Animation'),
    (7, 'Crime'),
    (8, 'Documentary'),
    (9, 'Drama'),
    (10, 'Family'),
    (11, 'Fantasy'),
    (12, 'Historical'),
    (13, 'Musical'),
    (14, 'Mystery'),
    (15, 'Romance'),
    (16, 'Science Fiction'),
    (17, 'Sports'),
    (18, 'Thriller'),
    (19, 'War'),
    (20, 'Western');

-- Insert data to MovieGenre table
INSERT INTO MovieGenre (MovieId, GenreId)
VALUES
    (4, 3),
    (4, 4),
    (5, 1),
    (5, 9),
    (6, 1),
    (6, 9),
    (7, 1),
    (7, 9),
    (8, 1),
    (8, 2);

-- Insert data to Reviews table
-- INSERT INTO Reviews (ReviewId, MovieId, UserId, Score, ReviewText)
-- VALUES
--     (1, 4, 1, 3.5,
--     'The Witch is a unique experience, and my introduction to the works of Robert Eggers. He''s quickly grown to be one of my favourite directors and The Witch is a great introduction. It''s authentic, the cast are invested in their characters and the setting is solid. It diverts from your standard horror tropes to focus on paranoia and mystery. The movie doesn''t really hit off until the very end, playing it extremely safe during the beginning and ending at its best. It''s final message also raises some questions during rewatches. If you''re looking for an immersive period piece surrounding the stigma of Witchcraft. You can''t do much better here.'),

--     (2, 4, 2, 3.5, 
--     '"The Witch" proves to be a unique and enigmatic cinematic experience, teetering between 2.5 and 3.5 stars. The deliberate, slow-paced horror build-up may not align with personal preferences, and the lack of traditional scary effects might disappoint some viewers. However, where the film truly shines is in its mysterious and suspenseful storytelling, amplified by stellar performances, especially from Anna Taylor-Joy. The movie''s eerie atmosphere and the depth of its narrative push it closer to a 3.5-star rating, showcasing that horror can transcend traditional scares and delve into psychological unease.'),

--     (3, 4, 3, 5.0, 
--     '"The Witch" stands as an exceptional piece in the realm of horror cinema, captivating audiences with its intense atmosphere and powerful imagery. Each shot in the film is meticulously crafted, speaking volumes without the need for words. The depiction of witchcraft is both beautiful and frightening, striking a perfect balance that intrigues and unnerves in equal measure. The soundtrack complements the visuals flawlessly, enhancing the eerie and unsettling ambiance of the film. This combination of striking imagery and haunting audio creates an atmosphere that is both mesmerizing and disconcerting, drawing the viewer deeper into the film''s dark world. The performances are outstanding, with each actor delivering a strong and compelling portrayal of their character. Their performances add depth to the already rich narrative, making the film''s tense and foreboding atmosphere even more palpable. What sets "The Witch" apart is its storytelling approach, which heavily relies on the interpretation of the viewer. The narrative is crafted in a way that encourages personal reflection and analysis, leaving much to the imagination and interpretation of the audience. While the film may not be conventionally scary, it has an undeniable psychological heaviness. Certain scenes are particularly impactful, resonating with a profound intensity that can be unsettling, especially for those in a low mood. In conclusion, "The Witch" is a remarkable horror film characterized by its powerful imagery, eerie soundtrack, and exceptional tension. It hits hard, leaving a lasting impact and opening doors for deep reflection and interpretation. It''s a must-watch for anyone seeking a thoughtful and artistically crafted horror experience.'),

--     (4, 4, 4, 5.0,
--     'The greatest terror is the fear that there is something evil hiding in the dark, especially around us! A puritan family lives in the woods and suffers a tragedy that leaves them suspicious of each other and soon tears them apart through paranoia. Gothic horror has never been more scarring then this, with the constant fear of the unknown permeating throughout!'),
    
--     (5, 4, 5, 4.0,
--     'Authentic sets and costumes, patient and smart cinematography and solid character development makes this one really worthwhile, as well the performance of Ralph Ineson and Kate Dickie. Most of the scares are so well done and they genuinely disturbed me. The score can be neat at times but not always. Same with the sound effects. Anya Taylor Joy is fine, although whenever she cries she can''t shed a single a tear, not even a fake one. That was a letdown. Harvey Scrimshaw''s perfomance is pretty decent for his age. The two little kids are horrendous for the most part.'),
    
--     (6, 5, 6, 2.5,
--     'Dangerously overrated cult classic that can be drawn to by girlfriends.'),
    
--     (7, 5, 7, 5.0,
--     'Best comedy ever made'),
    
--     (8, 5, 8, 1.0,
--     'One of the absolute most pointless piece garbage movies I have ever had the misery of enduring in my life!!!'),
    
--     (9, 5, 9, 5.0,
--     'The consummate Coen Brothers experience. Hilarious, twisty enough to keep you guessing, and so incredibly well acted by the entire cast. This movie is the gift that keeps giving.'),
    
--     (10, 5, 10, 4.5,
--     'A Comedy Classic well deserving of its cult status. Awesome soundtrack, amazing acting, hilarious jokes, and amazing editing. This is basically a screwball plot mixed in with a heist/ransom plot with surreal humor. The Dude is a poster child of the 90''s and a generation effecively. Walter is one of the most incredible characters I have ever seen. The characters and comedy are outright hilarious. The real only issue is this can get a little too abstract and complex for its own good sometimes. It''s well paced with really strong visuals and cinematography, but when it does go into the realm of abstract and surreal it feels like it breaks the momentum and humor at points like the dream sequence when hes knocked out. And things just get insanely crazy with the underlying ransom plot so much so that its hard to follow even without the abstract scenes. Anyone who is a fan of the Coen Brothers, actors in this, or quirky/out there comedies will like this. This is REALLY funny but really out there at points also.'),
    
--     (11, 6, 11, 5.0,
--     '"life is like a box of chocolates. you never know what you''re going to get" ❤️'),
    
--     (12, 6, 12, 5.0,
--     'An inspiring epic tale of a man who appears hopeless and stupid to many but proves people wrong time and time again through his compassion, determination and unequivocal honesty.'),
    
--     (13, 6, 13, 3.5,
--     'good film with a really sad ending Tom hanks is great in this i just thought it had a pretty sad tone throughout'),
    
--     (14, 6, 14, 5.0,
--     'As Forrest''s exploits are expertly (and oftentimes nonsensically) woven through some of history''s most famous events, the messages of the film - that love knows no bounds and that ordinary people are capable of extraordinary things - remain in their purest forms throughout the film. Hanks'' portrayal of this unlikely American icon lends credulity to his character''s extraordinary feats, keeping the unbelievable nature of his exploits closer to fantastical rather than inconceivable. Gump''s genuine nature grants us the perfect accents of simple humor and heartwarming emotion, simultaneously ameliorating the audience from over-the-top gags and overly saccharine moments. The iconic soundtrack stretches across decades worth of music''s most influential names, though at times can be a little too "on-the-nose". The selections could have benefited from a deeper dive into some of the artists'' catalogs in certain spots. Understandably, the idea was to capture certain moments in time through music, and in that regard, it does its job perfectly. When infused with Alan Silvestri''s epochal score, the film''s musical presence becomes another character whose presence we should all appreciate. "Forrest Gump" is what every moviegoer should get treated to whenever they step inside a theater, and its rare mastery of storytelling is why it remains one of the 20th century''s classics. It should continue to stand the test of time in the minds and hearts of movie lovers.'),
    
--     (15, 6, 15, 5.0,
--     'In high school, my teacher put this movie on after the majority of my classmates were done with the final. I''m a super slow test taker, so I had to complete the final while this movie was playing. I was beyond dead last completing that final from how hard I was laughing at this movie. Overall, this is definitely one of my favorite comedy movies; if not my favorite.'),
    
--     (16, 7, 16, 1.0,
--     'Not sure I can really write a review since I officially didn''t make it through the whole thing. Maybe it gets better but I doubt it. I started watching it because of the box art and thought the cast was spectacular, HAH! Turns out a movie needs more than star power, it needs the characters to actually show emotion and make the plot happen. It''s smart and stylish but forgets the humor in the process. Though I lied when I said I wouldn''t watch it again, I probably will when I need to fall asleep.'),
    
--     (17, 7, 17, 4.0,
--     'Delightful if overly eccentric film that is buoyed by Gene Hackman''s brilliant lead performance. The cast is superb, as with most Wes Anderson movies, with Hackman delivering one of his best lead performances as Royal, a despicable father who inadvertently learns he actually loves his family. Whether it is by teaching his grandchildren to shoplift or splitting flowers at gravesites, his Royal somehow remains charming even when doing some very bad things. Behind the camera, Anderson crafts another eccentric film, with some gorgeous shots and set pieces though it goes a bit too overboard at times (the romance between adopted siblings is a bit much). Overall, a fun watch, especially for one of Hackman''s best performances.'),
    
--     (18, 7, 18, 5.0,
--     'Fantastic movie!!!!!!'),
    
--     (19, 7, 19, 4.0,
--     'I don''t think that my family is weird after watching this.'),
    
--     (20, 7, 20, 5.0,
--     'Unique! Another must!'),
    
--     (21, 8, 21, 3.5,
--     'The Wild Hogs is an underrated movie, it''s just a slapstick comedy for easy laughs, there is no need to get technical about it, i have seen a lot worse cringe worthy films out there. I love these old silly movies, i feel like John Travolta is great in roles like this. It reminds me of his other movie Old Dogs, another easy comedy similar to this that guarantees a smile or two!'),
    
--     (22, 8, 22, 4.0,
--     'I have from its release thought this is one hilarious movie, I read the other reviews…chill it''s laughs and lite humor…not taken seriously and fun! 4 stars ⭐️'),
    
--     (23, 8, 23, 2.0,
--     'Some things make me laugh, but Travolta character was so disliked'),
    
--     (24, 8, 24, 4.0,
--     'Yeah it''s a little goofy, but it is also fun and entertaining. If you do not take yourself to seriously you will like this film.'),
    
--     (25, 8, 25, 4.5,
--     'This is why you should never look at the critic reviews. They''re always so opposite of what normal people enjoy. 14%? Cmon!!!! 🤦‍♂️ As a 52 year-old that went through mid-life crisis and had a Harley, this has to be my favorite comedy ever! It was so funny and I related so much and I loved it! Good movie and a good time!');