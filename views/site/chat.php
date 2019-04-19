<style>
    
.bd-example-container {
  min-width: 16rem;
  max-width: 25rem;
  margin-right: auto;
  margin-left: auto;
}

.bd-example-container-header {
  height: 3rem;
  margin-bottom: .5rem;
  background-color: lighten($blue, 50%);
  @include border-radius;
}

.bd-example-container-sidebar_right {
  float: right;
  width: 4rem;
  height: 8rem;
  background-color: purple;
  @include border-radius;
}

.bd-example-container-sidebar_left {
  float: left;
  width: 4rem;
  height: 8rem;
  background-color: blue;
  @include border-radius;
}

.bd-example-container-body {
  height: 8rem;
  margin-right: 4.5rem;
  background-color: green;
  @include border-radius;
}

.bd-example-container-fluid {
  max-width: none;
}
    </style>

    <div class="row">
        <div class='col-xs-12 header'>Header</div>
    </div>
    <div class='row'>
        <div class='col-xs-4 menu'> Menu</div>
        <div class='col-xs-6 content'> CONTENT</div>
        <div class='col-xs-2 content'> chat</div>
    </div>
    <div class="row">
        <div class='col-xs-12 footer'>FOOTER</div>
    </div>

<!--<div class="container-fluid">
    
<div class="bd-example-container-sidebar_left">menu</div>
    <div class="bd-example-container-body">corpo</div>
    <div class="bd-example-container-sidebar_right">chat</div>

  </div>-->