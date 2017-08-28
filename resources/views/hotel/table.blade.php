  <?php 
    if(is_array($hotelData) && count($hotelData)>0) 
    {   
        
        foreach($hotelData as $kby=>$hotelval)
        { 
    ?>
              <tr>
                <td>{{ $hotelval['name'] }}</td>
                <td>${{ $hotelval['price'] }}</td>
                <td>{{ $hotelval['city'] }}</td>
                <td>{!! $hotelval['availability'] !!} </td>
              </tr>
    <?php
        }
    }
    ?> 