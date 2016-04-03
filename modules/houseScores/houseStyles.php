<style>
  #foxell  { background-color: #fac800; }
  #holman  { background-color: #e87b19; }
  #newman  { background-color: #d32918; color: #ffffff; }
  #pearson { background-color: #96c4f8; }
  #rayner  { background-color: #2542a1; color: #ffffff; }
  #thorne  { background-color: #247703; color: #ffffff; }
  
  .nav-pills {
    margin-bottom: 15px;
  }
  .nav-pills li {
    float: right;
    margin-left: 5px;
  }
  .nav-pills > li > a {
    border-radius: 0;
    padding: 0 10px;
    color: #111;
  }
  .nav-pills > li.active > a, .nav-pills > li.active > a:hover, .nav-pills > li.active > a:focus {
    background-color: #777;
    cursor: default;
  }
  
  .houseDisplay {
    height: 220px;
  }
  .houseDisplay .tab-pane {
    display: block;
    height: 0;
  }
  
  .houseTotals {
    height: 32px;
    margin-bottom: 3px;
    border-radius: 0;
    background-color: #fff;
    box-shadow: none;
  }
    .houseTotals.leader {
      height: 40px;
      margin: 4px 0;
    }
    .houseTotals.leader .progress-bar {
      font-size: 22px;
      padding: 11px 6px 0;
    }
  .houseTotals .progress-bar {
    text-align: left;
    font-size: 18px;
    font-weight: bold;
    color: #111;
    padding: 7px 6px 0;
    box-shadow: none;
  }
    .houseTotals .progress-bar span {
      float: right;
      font-size: 15px;
      padding-right: 5px;
    }
    .houseTotals.leader .progress-bar span {
      font-size: 18px;
    }
  
  .houseKey {
    margin: 15px 0 0;
  }
  .houseKey p {
    margin: 10px -15px 0;
  }
  .houseKey .col-xs-2 p {
    text-align: center;
    font-weight: bold;
    margin: 0;
  }
  
  #houseBreakdown .panel {
    box-shadow: none;
  }
  #houseBreakdown .panel-heading h4 {
    font-family: "Quattrocento Sans", Helvetica, sans-serif;
    font-size: 18px;
  }
  #houseBreakdown .panel-heading {
    padding: 0;
  }
  #houseBreakdown .panel-heading a {
    display: block;
    padding: 10px 15px;
    background-color: #f5f5f5;
  }
  #houseBreakdown .panel-heading a:hover, #houseBreakdown .panel-heading a:focus {
    background-color: #ccc;
    text-decoration: none;
  }
  #houseBreakdown .panel-body {
    padding: 5px;
  }
  #houseBreakdown .table, #houseBreakdown .table p, #houseBreakdown .table h4 {
    margin: 0;
  }
  #houseBreakdown th, #houseBreakdown td {
    width: 20%;
  }
  #houseBreakdown th:first-child, #houseBreakdown td:first-child {
    width: 40%;
  }
  #houseBreakdown td {
    border: 0;
  }
  #houseBreakdown .place {
    text-align: center;
  }
  #houseBreakdown .table p.place {
    margin: 0 -5px;
    padding: 2px 0;
  }
  
  .podium .col-xs-3 {
    padding: 5px;
    text-align: center;
  }
  .podium .col-xs-3 h1 {
    font-size: 40px;
    margin: 0;
    padding-top: 5px;
  }
  .podium .col-xs-3 p {
    font-size: 18px;
    margin-bottom: 5px;
  }
  .podium .col-xs-3:first-child {
    margin-left: 12.5%;
  }
    .podium .first h1 {
      height: 140px;
    }
    .podium .first p {
      font-weight: bold;
    }
    .podium .second h1 {
      height: 100px;
    }
    .podium .second p {
      margin-top: 40px;
    }
    .podium .third h1 {
      height: 70px;
    }
    .podium .third p {
      margin-top: 70px;
    }
  .champions h1 {
    font-size: 48px;
    text-align: center;
    margin-top: 10px;
  }
  .champions h3 {
    margin: 10px 0 5px;
  }
  .champions p {
    font-size: 18px;
    margin: 0;
  }
  .honourRoll {
    margin: 0 0 15px;
  }
  .honourRoll p {
    margin: 0;
    padding: 0 8px;
    line-height: 1.4;
  }
    .honourRoll p:first-of-type {
      font-weight: bold;
      margin-top: 5px;
    }
  .honourRoll h3 {
    margin: 0;
    padding: 5px 8px;
  }
  .honourRoll > div {
    padding: 0;
  }
  
  .twitterTable .barLink {
    margin: 0;
  }
  .twitterTable td {
    border-top: 0!important;
  }
  .houseLists h2 {
    margin: 20px -5px 0;
    padding: 5px;
  }
  .houseLists h3 {
    margin-top: 10px;
  }
  
  .overallRanks {
    margin-top: -40px;
  }
  .overallRanks th {
    font-weight: normal;
    font-size: 16px;
  }
  .overallRanks th, .overallRanks td {
    border: 0!important;
    text-align: center;
    width: 15%;
    padding: 8px 8px 6px!important;
  }
  .overallRanks th:first-child, .overallRanks td:first-child {
    width: 10%;
  }
  .overallRanks th:nth-child(2), .overallRanks td:nth-child(2) {
    text-align: left;
    width: 45%;
  }
  .overallRanks p {
    margin: 0;
    font-weight: bold;
    font-size: 18px;
  }
</style>