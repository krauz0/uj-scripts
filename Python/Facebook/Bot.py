from flask import Flask, request
from pymessenger.bot import Bot
import requests
import datetime

app = Flask(__name__)
ACCESS_TOKEN = 'EAAb2ZAV6sn40BAMpkk1pPdQ61yCvGaisTeFZCHzhsO3e4EzflR5mHri86Sr94PTwAs246ZBX8zZCVT068OQtUTW1gVkmZAu4ZAQfCSjZCP7vHXVuIKJq4AhrxssQk5I9IJiUWOGZCbenFstdyZA7lTsBGUu8rx7gviZC3uK4v6JEQ2yGzX1VbkhZCZCS'
VERIFY_TOKEN = 'abcdef'
bot = Bot(ACCESS_TOKEN)

@app.route("/", methods=['GET', 'POST'])
def receive_message():
    if request.method == 'GET':
        token_sent = request.args.get("hub.verify_token")
        return verify_fb_token(token_sent)
    else:
        output = request.get_json()
        for event in output['entry']:
            messaging = event['messaging']
            for message in messaging:
                if message.get('message'):
                    recipient_id = message['sender']['id']
                    if message['message'].get('text'):
                        response_sent_text = get_message()
                        send_message(recipient_id, response_sent_text)
        return "Message Processed"


def verify_fb_token(token_sent):
    if token_sent == VERIFY_TOKEN:
        return request.args.get("hub.challenge")
    return 'Invalid verification token'


def get_message():
    response = "Hi! Here is Greg, your stock market advisor. Give me the symbol to check for you.\n\n"
    response += "Daily series for APPL:\n"
    response += get_daily("AAPL")
    return response


def send_message(recipient_id, response):
    #bot.send_text_message(recipient_id, response)
    button = """{
            "type": "phone_number",
            "title": "Zadzwoń do mnie",
            "payload": "+48xxxxxxxxx" 
        }"""
    bot.send_button_message(recipient_id, response, [button])
    return "success"


def get_daily(symbol):
    URL = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol="+symbol+"&apikey=6EOUQ4MC6G4C9VDN&outputsize=compact"
    response = requests.get(URL)

    if response.status_code == 200:
        json = response.json()
        resp = ""

        days = json['Time Series (Daily)']
        last_series_key = list(days.keys())[0]
        resp += last_series_key + ":\n"
        daily = days[last_series_key]
        for val in daily:
            resp += val + ": " + daily[val] + "\n"
        print(resp)
        return resp
    else:
        print("Something is wrong!")
        return "Something is wrong!"


if __name__ == "__main__":
    app.run(port=3000)
