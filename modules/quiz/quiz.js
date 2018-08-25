class Quiz {
    constructor(root) {
        // 'root' is the ID of the div you would like the form to be constructed inside of

        document.getElementById(root).innerHTML =
        `<div class="container">
            <div class="row">
          <div class="col-xs-12">
                  <h1 id="quizTitle"></h1>
          </div>
            </div>
            <div class="row">
          <div class="col-xs-12">
            <div class="progress">
              <div class="progress-bar" role="progressbar" id="quizProgress"></div>
            </div>
          </div>
            </div>
            <div class="row">
          <div class="col-xs-12" id="quizContent"></div>
        </div>
            <div class="row">
          <div class="col-xs-12">
            <form id="answerForm" autocomplete="off">
              <div class="row" id="answerInput">
            </form>
          </div>
            </div>
        </div>`;
    }

    shuffleArray(arr) {
        for (let i = arr.length - 1; i > 0; i--) {
            let j = Math.floor(Math.random() * (i + 1));
            let temp = arr[i];
            arr[i] = arr[j];
            arr[j] = temp;
        }
        return arr;
    }

    constructOptions() {
        $('#answerInput').empty();
        $('#answerInput').append(
            `<div class="col-sm-12">
            <div class="row" id="choiceOptions">
            </div>
            </div>`
        );
        var countAnswers = Object.keys(this.quizData['questions'][this.countCurrent-1]['answer']).length-1;
        var col = ((countAnswers == 3 || countAnswers >= 5) ? 4 : 6);

        // Get option data and remove filter to only include values and shuffle
        let options = Object.values(this.quizData['questions'][this.countCurrent-1].answer).filter(
            (answer, index) => Object.keys(this.quizData['questions'][this.countCurrent-1].answer)[index] !== 'c'
        );
        options = this.shuffleArray(options);

        // Construct options markup
        let optionsRTN = options.map( (answer, index) => `
            <div class=${'col-xs-' + col}>
            <input type="radio" id=${'answer-' + index} name="choice" value=${answer} onclick="document.getElementById('answerForm').dispatchEvent(new Event('submit'))" />
            <label for=${'answer-' + index}>${answer}</label>
            </div>`
        ).join('');
        document.getElementById('choiceOptions').insertAdjacentHTML('beforeend', optionsRTN);
    }

    buildQuestion() {
        let quizPosition = (this.countCurrent - 1) * 100 / this.countTotal;
        $('#quizProgress').css('width', quizPosition + '%');
        $('#quizContent').html('<h2>Question ' + this.countCurrent + ' of ' + this.countTotal + '</h2>');
        $('#quizContent').append(this.quizData['questions'][this.countCurrent-1].content);
        if (this.quizData['questions'][this.countCurrent-1].format != 'choice') {
            $('#answerInput').html(
                '<div class="col-sm-2 col-xs-3">' +
                '<label for="answerBox">' +
                'Answer:' +
                '</label>' +
                '</div>' +
                '<div class="col-sm-8 col-xs-6">' +
                '<input type="text" id="answerBox" />' +
                '</div>'
            );
            $(':input[type="text"]').focus();
            $('#answerInput').append(
                '<div class="col-sm-2 col-xs-3">' +
                '<button type="submit">Submit</button>' +
                '</div>'
            );
        } else {
            this.constructOptions()
        }
        MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
    }

    correctAnswer() {
        if (this.countCurrent < this.countTotal) {
            this.countCurrent++;
            this.buildQuestion();
        } else {
            $('#quizProgress').css('width', '100%');
            $('#answerInput').remove();
            let winnerMessage = ['Congratulations!', 'Well done!', 'Great job!', 'Nice one!', 'Good work!', 'Awesome!', 'Hooray!'];
            let messageNumber = Math.floor(Math.random() * (winnerMessage.length - 1))
            $('#quizTitle').html(winnerMessage[messageNumber]);
            $('body').addClass('winning');
            $('#quizContent').html('<p>You have completed this quiz!</p>');
            if (this.quizData['id']) {
                $('#quizContent').append('<p>The winning code is <b>' + $.base64.decode(this.quizData['id']) + '</b>.<p>');
            }
        }
    }

    wrongAnswer() {
        if (this.quizData['questions'][this.countCurrent-1].format != 'choice') {
            $(':input[type="submit"]').prop('disabled', true).addClass('disabled');
            $('#wrongAnswer').css({'visibility' : 'visible'});
            $('#answerInput').append(
                `<div class="row" id="wrongAnswer">
                <div class="col-xs-12">
                <p>That's not correct. Check your working and try again.</p>
                </div>
                </div>`
            );
        } else {
            $('#answerInput').append(
                `<div class="row" id="wrongAnswer">
                <div class="col-xs-12">
                <p class="disabled"></p>
                <p>That's not correct. Check your working and try again.</p>
                </div>
                </div>`
            );
            $('#choiceOptions').css({'display' : 'none'});
        }

        $('.disabled').pietimer({
            seconds: 4,
            color: 'rgba(0, 0, 0, 0.8)',
            height: 16, width: 16
        },
        () => {
            $(':input[type="submit"]').prop('disabled', false).removeClass('disabled');
            $(':input[type="submit"]').html('Submit');
            $('#choiceOptions').css({'display' : ''});
            $('#wrongAnswer').remove();
            if (this.quizData['questions'][this.countCurrent-1].format === 'choice') this.constructOptions();
        });
        $('.disabled').pietimer('start');
    }

    submitForm(event) {
        event.preventDefault();
        if (this.quizData['questions'][this.countCurrent-1].format != 'choice') {
            if (this.quizData['questions'][this.countCurrent-1].format === 'loose') {
                var answerToCheck = $('input#answerBox').val().toLowerCase().trim();
                answerToCheck = answerToCheck.replace(/ /g,'-');
                answerToCheck = answerToCheck.replace(/[^a-z0-9\-]/g,'');
                answerToCheck = answerToCheck.replace(/-+/g,'-');
                answerToCheck = md5(answerToCheck);
            } else {
                var answerToCheck = md5($('input#answerBox').val().trim());
            }
            if ($.inArray(answerToCheck,this.quizData['questions'][this.countCurrent-1].answer) != -1) {
                this.correctAnswer();
            } else {
                this.wrongAnswer();
            }
        } else {
            var answerToCheck = $('input[name="choice"]:checked').val();
            answerToCheck = md5(answerToCheck);
            if (answerToCheck == this.quizData['questions'][this.countCurrent-1].answer['c']) {
                this.correctAnswer();
            } else {
                this.wrongAnswer();
            }
        }
    }

    async generateQuiz(author, fileName) {
        let response = await fetch('/data/quiz/' + author + '/' + fileName + '.json');
        let json = await response.json();
        json['_'] = new Date().getTime();
        console.log(json);
        $('#quizTitle').html(json.name);
        this.quizData     = json;
        this.countCurrent = 1;
        this.countTotal   = this.quizData['questions'].length;
        this.buildQuestion();
        document.getElementById('answerForm').addEventListener("submit", this.submitForm.bind(this));
    }
}
