<style>
  body {
    background-image: url('/img/errorGlobal.png');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: right bottom;
    background-size: 120%;
  }
  @media (min-width: 768px) {
    body {
      background-size: 70%;
    }
  }
  .globalError a {
    font-weight: bold;
    text-decoration: underline;
  }
  .globalError a:hover {
    color: #000;
  }
  .globalError .col-xs-3 {
    text-align: center;
    margin-top: 10px;
    color: Orange;
  }
</style>

<div class="row globalError">
  <div class="col-xs-3">
    <i class="fas fa-exclamation-triangle fa-5x"></i>
  </div>
  <div class="col-xs-9">
    <h1>Oh no!</h1>
    <p>Sorry, it looks like this data is missing or broken (or perhaps... it was <em>never there at all</em>).</p>
    <p>You should <a href="/">go back to the start</a> and giving it another go. Or, if that doesn't work out, you could <a href="/c/Information/general-information/contact-us">contact us</a> to report the problem.</p>
  </div>
</div>