# GobblerExchange

Implemented:
- User can view, create, edit, and delete forum posts, calendar events, notes, polls, forum comments, and notes comments
- User can upvote/downvote forum posts and notes
- Null checks are implemented. If the forum has 0 posts, the calendar has 0 upcoming events, there are 0 notes, there are 0 comments, or there are 0 polls, the website says "There doesn't seem to be any ____"
- User can open/close polls; Open polls will allow user interaction and closed polls will say [closed] and won't allow the user to submit their response.
- Polls will save a user's response so that they can change their answer later
- Main search bar provides some functionality
    - Search by CRN/email/username: works if given the exact term
    - Search by Group: provides results if the search term is in the group name (order matters)
- User authentication

Not Implemented (yet):
- Only allowing the user who authored the post/event/notes/comment to edit/delete it (currently, anyone can edit or delete any post/event/notes/comment)
- Searching for forum posts
- Delete/edit comments
- Checking if input fields are empty
- Forum posts ordered by rating (currently ordered by date/time)
- Whiteboard
- Join button when searching for groups
- PDF view preview for notes before downloading
- User must join/create a group when they sign up
- Adding users to a group when a user creates a group
