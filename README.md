# Ultron_Twitch

## About

Ultron_twitch is meant to help streamers monitor their "competition" on a given category over a given period of time.
Twitch displays how many channels are live at insant T in a specific Twitch category but does not provide history.
The Ultron should request via Twitch API the list of channels on a given category (with their viewers count, stream title, channel name, language) every five minutes. The app extracts and stores data.

### Project phases

- Phase 1 : simple file storage
- Phase 2 : task automation
- Phase 3 : storing in database
- Phase 4 : producing charts

### Consider:

- how to admin the list of categories?
- how to request several categories (when one game = different categories)
