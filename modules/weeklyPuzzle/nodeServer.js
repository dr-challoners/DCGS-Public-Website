var app = require('http').createServer(handler);
var io = require('socket.io')(app);
var fs = require('fs');
var consolere = require('console-remote-client').connect('console.re','80','puzzleChannel');
var JSONquery = require("underscore");
var correctPoints = 1;
var checkBefore;
var JSONpath = 'public_html/data/modules/weeklyPuzzle/user_data/';
app.listen(8080,function(){console.re.log('listening')});
fs.writeFile(JSONpath + 'master_score.json', '{"scores":[]}', function (err) {
  console.re.log('Error is ' + err);
});
io.on('connection', function(socket){
    socket.on('Pong',function(data){
      //console.re.log('Received data');
      if (data.email != undefined){
        //console.log(data.email);
        fs.exists(JSONpath + data.email + '.json', function(exists) {
          if (exists) {
            console.re.log('JSON for ' + data.email + ' is already made. Appending data...');
            appendData(data.email,data);
            if (checkBefore=="DONE" || "NOTDONE"){
                      socket.emit('Ping',checkBefore);
                      console.re.log('Sending Data of completion check');
                      checkBefore = "";
            }
          } else {
            console.re.log('Making new JSON for ' + data.email);
            writeJSON(data.email,data);
            if (checkBefore=="DONE" || "NOTDONE"){
                      socket.emit('Ping',checkBefore);
                      console.re.log('Sending Data of completion check');
                      checkBefore = "";
            }
          }
        });
      }
      else {
        //console.log(data);
        console.re.log('Sending score of ' + data.emailId +  ' over')
        fs.exists(JSONpath + data.emailId + '.json', function(exists) {
          if (exists) {
            var configFile = fs.readFileSync(JSONpath+ data.emailId + '.json');
            var config1 = JSON.parse(configFile);
            var filtered = JSONquery.where(config1.results, {"correct": 1});
            var evens = JSONquery.filter(filtered, function(num){ return num.week < data.week });
            var filtered1 = JSONquery.where(config1.results, {"week": data.week});
            console.re.log(filtered1);
            if (filtered1.length == 0){
              socket.emit('Ping',{'numCorrect' : evens.length ,'totalDone' : config1.results.length,'doneBefore' : '0'});
            }
            else {
              socket.emit('Ping',{'numCorrect' : evens.length ,'totalDone' : config1.results.length,'doneBefore' : '1'});
              console.re.log('doneBefore');
            }
        //console.log(evens.length);
          }
        });
      }
    });
});

function handler (req, res) {
  fs.readFile(__dirname + '/index.html',
  function (err, data) {
    if (err) {
      res.writeHead(500);
      return res.end('Running node Server');
    }
res.writeHead(200, {
    "Content-Type": "text/plain",
    "Access-Control-Allow-Origin":"*"
});
res.header('Access-Control-Allow-Origin', "*")
  });
}
function appendData(emailId,obj){
       var configFile = fs.readFileSync(JSONpath+ emailId + '.json');
        var config1 = JSON.parse(configFile);
        var filtered = JSONquery.where(config1.results, {"week": obj.results[0].week});
        if (filtered.length == 0){
          config1.results.push({"week":obj.results[0].week,"answer":obj.results[0].answer,"correct": obj.results[0].correct});
          var configJSON = JSON.stringify(config1);
          fs.writeFileSync(JSONpath + emailId + '.json', configJSON);
          addToScoreJSON(config1,obj);
          checkBefore = "NOTDONE";
          console.re.log('Written data into ' + emailId + '.json');
        }
        else {
          checkBefore = 'DONE';
           console.re.log('Puzzle already completed by ' + emailId);

        }
}
function writeJSON(emailId,obj){
  fs.writeFile(JSONpath + emailId + '.json', JSON.stringify(obj), function (err) {
    if (err) return console.log(err);
    console.re.log('Created JSON for ' + emailId);
    var configFile = fs.readFileSync(JSONpath+ emailId + '.json');
    var config1 = JSON.parse(configFile);
    addToScoreJSON(config1,obj);
    checkBefore = 'NOTDONE';
  });
}
function addToScoreJSON(configData,obj){
  console.re.log('Adding score to master_score.json for ' + obj.email);
  var filtered = JSONquery.where(configData.results, {"correct": 1});
  var user_score = filtered.length * correctPoints;
  var scoreFile = fs.readFileSync(JSONpath + 'master_score.json');
  var score = JSON.parse(scoreFile);
  findAndRemove(score.scores, 'email', obj.email);
  score.scores.push({"email":obj.email,"total_score":user_score});
  var scoreJSON = JSON.stringify(score);
  fs.writeFileSync(JSONpath + 'master_score.json', scoreJSON);
}
function findAndRemove(array, property, value) {
  array.forEach(function(result, index) {
    if(result[property] === value) {
      array.splice(index, 1);
    }
  });
}