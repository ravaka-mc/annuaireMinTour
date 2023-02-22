// $(function() {
//   'use strict';
//   /*Activate default tab contents*/
//   var leftPos, newWidth, $magicLine;

//   $('.blog_grid').append("<div class='hover'></div>");
//   $('.blog_grid .card-item:nth-child(2)').addClass('active');
//   $magicLine = $('.hover');
//   $magicLine.width($('.blog_grid .active').innerWidth())
//     .css('left', $('.blog_grid .active').position().left)
//     .data('origLeft', $magicLine.position().left)
//     .data('origWidth', $magicLine.innerWidth());

//   /*Magicline hover animation*/
//   $('.blog_grid .card-item').hover(function() {
//     var $thisBar = $(this);
//     leftPos = $thisBar.position().left;
//     newWidth = $thisBar.innerWidth();
//     $magicLine.css({
//       "left": leftPos,
//       "width": newWidth
//     });
//   }, function() {
//     $magicLine.css({
//       "left": $magicLine.data('origLeft'),
//       "width": $magicLine.data('origWidth')
//     });
//   });
// });