# GobblerExchange

Implemented:
- User can create groups
  - By CRN, for most classes offered this semester. This will match the crn to its course and provide the proper name for the group
  - By name, for non class groups
- User can view, create, edit, and delete forum posts, calendar events, notes, polls, forum comments (create/view only), and notes comments (create/view only)
- User can upvote/downvote forum posts and notes
- Null checks are implemented. If the forum has 0 posts, the calendar has 0 upcoming events, there are 0 notes, there are 0 comments, or there are 0 polls, the website says "There doesn't seem to be any ____"
- User can open/close polls; Open polls will allow user interaction and closed polls will say [closed] and won't allow the user to submit their response.
- Polls will save a user's response so that they can change their answer later
- Main search bar provides some functionality
    - Search by CRN/email/username: works if given the exact term
    - Search by Group: provides results if the search term is in the group name (order matters)
- Forum search bar searches for phrases in post titles and descriptions.
- User account creation
  - Checks for repeated username, checks for vt.edu email address, checks if passwords match
- User log in authentication
- Forum posts ordered by rating (decreasing)
- Notes are ordered by rating (decreasing)
- Events are ordered by date (increasing)
- Polls are ordered by date (decreasing)
    - Only upcoming events are shown
- Creating events
    - date and time must be entered in the specified format
- Can join group when searching for groups
- Searching w/ no term will produce all results (ex. if 'username' is selected w/ no search term, it'll return all users)
- Users can chat with one another in seperate chat rooms for each class (and a global /all chat).
- Notes are able to be uploaded/downloaded

Not Implemented (yet):
- Dynamic CRN matching. Currently uses hardcoded JSON file of classes from the current semester, instead of dynamically scraping the timetable. (Michael)
- Leave a group (Megan)
- Edit and delete forum & note comments (Megan)
- Only allowing the user who authored the post/event/notes/comment to edit/delete it (currently, anyone can edit or delete any post/event/notes/comment) (Megan)
- Checking if form input fields are empty (Alec)
  - Check for special characters
  - Check for correct format (like the time/date)
- Whiteboard (Greg)
- PDF view preview for notes before downloading 
- User must join/create a group when they create an account (Alec)
- Ability to add/invite users to a group when a user creates a group (Alec)
- Sorting forum posts via dropdown (Alec)
- Put pinned posts at the top (Megan)
- Make entering the dates user-friendly (https://jqueryui.com/datepicker/) (Megan)
- Scalability (only looks good on desktop/laptop screens for now) (Front-end team)
- Show poll results (Megan)
- View past events (Megan)
- Chat room visibility does not update except for upon login. (Greg)
- Proper tag categories (Megan)
- Pin/unpin a post (Megan)
- Delete poll response (Megan)

