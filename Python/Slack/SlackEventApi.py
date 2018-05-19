from flask import Flask, request
from slackeventsapi import SlackEventAdapter
from slackclient import SlackClient

VERIFICATION_TOKEN = "JPcr21Tv0LK9UDv6doh5t1Ae"

app = Flask(__name__)

slack_events_adapter = SlackEventAdapter(VERIFICATION_TOKEN, "/slack/events", app)


@slack_events_adapter.on("reaction_added")
def reaction_added(event):
    emoji = event.get("event").get("reaction")
    print(emoji)


@slack_events_adapter.on("message")
def message_resp(event):
    message = event.get("event").get("text")

    if event.get("event").get("bot_id"):
        return

    sc.api_call("chat.postMessage", channel=event.get("event").get("channel"),
                text="¯\_(ツ)_/¯ " + message, as_user=False)

@app.route("/repeat",methods=['GET','POST'])
def repeat():
    pass

if __name__ == "__main__":
    sc = SlackClient("xoxb-358403383152-KA9OD6zrZj8smGtAN3PH1Yft")

    if not sc.rtm_connect():
        print("RTM API Connection failed")
        exit(-1)

    app.run(port=3000)

