from flask import Flask, request


app = Flask(__name__)
@app.route("/slack/events",methods=['GET','POST'])
def authorize():
  output = request.get_json()
  return output["challenge"]

if __name__ == "__main__":
  app.run(port=3000)

