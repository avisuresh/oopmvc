<?php
//UrL redirect helpers

function redirect($page){
    header('location:'.URLROOT.'/'.$page);
}